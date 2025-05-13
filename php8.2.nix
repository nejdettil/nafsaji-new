{ pkgs ? import <nixpkgs> {} }:

pkgs.stdenv.mkDerivation rec {
  pname = "php82";
  version = "8.2";

  buildInputs = with pkgs; [
    php82
    php82Extensions.pdo
    php82Extensions.pdo_mysql
    php82Extensions.mbstring
    php82Extensions.exif
    php82Extensions.bcmath
    php82Extensions.gd
    php82Extensions.intl
    php82Extensions.mysqli
    php82Extensions.curl
    php82Extensions.zip
    php82Extensions.opcache
  ];

  shellHook = ''
    echo "PHP 8.2 development environment loaded"
  '';
}
