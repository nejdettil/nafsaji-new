<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowTableColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:table:columns {table : The name of the table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all columns of a specific table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist.");
            return 1;
        }
        
        $columns = Schema::getColumnListing($table);
        
        if (empty($columns)) {
            $this->info("No columns found for table '{$table}'.");
            return 0;
        }
        
        $rows = [];
        foreach ($columns as $column) {
            $type = DB::connection()->getDoctrineColumn($table, $column)->getType()->getName();
            $nullable = !DB::connection()->getDoctrineColumn($table, $column)->getNotnull() ? 'YES' : 'NO';
            
            $rows[] = [
                'column' => $column,
                'type' => $type,
                'nullable' => $nullable,
            ];
        }
        
        $this->table(
            ['Column', 'Type', 'Nullable'],
            $rows
        );
        
        return 0;
    }
}
