## Begin Server manifest

if $server_values == undef {
  $server_values = hiera('server', false)
}

group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

user { $::ssh_username:
    shell  => '/bin/bash',
    home   => "/home/${::ssh_username}",
    ensure => present
}

file { "/home/${::ssh_username}":
    ensure => directory,
    owner  => $::ssh_username,
}

# in case php extension was not loaded
if $php_values == undef {
  $php_values = hiera('php', false)
}

# copy dot files to ssh user's home directory
exec { 'dotfiles':
  cwd     => "/home/${::ssh_username}",
  command => "cp -r /vagrant/files/dot/.[a-zA-Z0-9]* /home/${::ssh_username}/ && chown -R ${::ssh_username} /home/${::ssh_username}/.[a-zA-Z0-9]*",
  onlyif  => "test -d /vagrant/files/dot",
  require => User[$::ssh_username]
}

# debian, ubuntu
case $::osfamily {
  'debian': {
    class { 'apt': }

    Class['::apt::update'] -> Package <|
        title != 'python-software-properties'
    and title != 'software-properties-common'
    |>

    ensure_packages( ['augeas-tools'] )
  }
}

case $::operatingsystem {
  'debian': {
    add_dotdeb { 'packages.dotdeb.org': release => $lsbdistcodename }

    if is_hash($php_values) {
      # Debian Squeeze 6.0 can do PHP 5.3 (default) and 5.4
      if $lsbdistcodename == 'squeeze' and $php_values['version'] == '54' {
        add_dotdeb { 'packages.dotdeb.org-php54': release => 'squeeze-php54' }
      }
      # Debian Wheezy 7.0 can do PHP 5.4 (default) and 5.5
      elsif $lsbdistcodename == 'wheezy' and $php_values['version'] == '55' {
        add_dotdeb { 'packages.dotdeb.org-php55': release => 'wheezy-php55' }
      }
    }
  }
  'ubuntu': {
    apt::key { '4F4EA0AAE5267A6C': }

    if is_hash($php_values) {
      # Ubuntu Lucid 10.04, Precise 12.04, Quantal 12.10 and Raring 13.04 can do PHP 5.3 (default <= 12.10) and 5.4 (default <= 13.04)
      if $lsbdistcodename in ['lucid', 'precise', 'quantal', 'raring'] and $php_values['version'] == '54' {
        if $lsbdistcodename == 'lucid' {
          apt::ppa { 'ppa:ondrej/php5-oldstable': require => Apt::Key['4F4EA0AAE5267A6C'], options => '' }
        } else {
          apt::ppa { 'ppa:ondrej/php5-oldstable': require => Apt::Key['4F4EA0AAE5267A6C'] }
        }
      }
      # Ubuntu Precise 12.04, Quantal 12.10 and Raring 13.04 can do PHP 5.5
      elsif $lsbdistcodename in ['precise', 'quantal', 'raring'] and $php_values['version'] == '55' {
        apt::ppa { 'ppa:ondrej/php5': require => Apt::Key['4F4EA0AAE5267A6C'] }
      }
      elsif $lsbdistcodename in ['lucid'] and $php_values['version'] == '55' {
        err('You have chosen to install PHP 5.5 on Ubuntu 10.04 Lucid. This will probably not work!')
      }
    }
  }
}

ensure_packages( $server_values['packages'] )

define add_dotdeb ($release){
   apt::source { $name:
    location          => 'http://packages.dotdeb.org',
    release           => $release,
    repos             => 'all',
    required_packages => 'debian-keyring debian-archive-keyring',
    key               => '89DF5277',
    key_server        => 'keys.gnupg.net',
    include_src       => true
  }
}

## Begin Apache manifest

if $apache_values == undef {
  $apache_values = hiera('apache', false)
}

class { 'apache':
  user          => $apache_values['user'],
  group         => $apache_values['group'],
  default_vhost => $apache_values['default_vhost'],
  mpm_module    => $apache_values['mpm_module']
}

case $apache_values['mpm_module'] {
  'prefork': { ensure_packages( ['apache2-mpm-prefork'] ) }
  'worker':  { ensure_packages( ['apache2-mpm-worker'] ) }
  'event':   { ensure_packages( ['apache2-mpm-event'] ) }
}

create_resources(apache::vhost, $apache_values['vhosts'])

define apache_mod {
  class { "apache::mod::${name}": }
}

if count($apache_values['modules']) > 0 {
  apache_mod { $apache_values['modules']:; }
}

## Begin PHP manifest

if $php_values == undef {
  $php_values = hiera('php', false)
}

if $apache_values == undef {
  $apache_values = hiera('apache', false)
}

if $nginx_values == undef {
  $nginx_values = hiera('nginx', false)
}

Class['Php'] -> Class['Php::Devel'] -> Php::Module <| |> -> Php::Pear::Module <| |> -> Php::Pecl::Module <| |>

if is_hash($apache_values) {
  class { 'php':
    service => 'httpd'
  }
} elsif is_hash($nginx_values) {
  class { 'php':
    package             => 'php5-fpm',
    service             => 'php5-fpm',
    service_autorestart => false,
    config_file         => '/etc/php5/fpm/php.ini',
  }

  service { 'php5-fpm':
    ensure     => running,
    enable     => true,
    hasrestart => true,
    hasstatus  => true,
    require    => Package['php5-fpm']
  }
}

class { 'php::devel': }

if count($php_values['modules']['php']) > 0 {
  php_mod { $php_values['modules']['php']:; }
}
if count($php_values['modules']['pear']) > 0 {
  php_pear_mod { $php_values['modules']['pear']:; }
}
if count($php_values['modules']['pecl']) > 0 {
  php_pecl_mod { $php_values['modules']['pecl']:; }
}

define php_mod {
  php::module { $name: }
}
define php_pear_mod {
  php::pear::module { $name: use_package => false }
}
define php_pecl_mod {
  php::pecl::module { $name: use_package => false }
}

if $php_values['composer'] == 1 {
  class { 'composer':
    target_dir      => '/usr/local/bin',
    composer_file   => 'composer',
    download_method => 'curl',
    logoutput       => false,
    tmp_path        => '/tmp',
    php_package     => 'php5-cli',
    curl_package    => 'curl',
    suhosin_enabled => false,
  }
}

## Begin XDebug manifest

if $xdebug_values == undef {
  $xdebug_values = hiera('xdebug', false)
}

if is_hash($apache_values) {
  $xdebug_webserver_service = 'httpd'
} elsif is_hash($nginx_values) {
  $xdebug_webserver_service = 'nginx'
} else {
  $xdebug_webserver_service = undef
}

if $xdebug_values['install'] != undef and $xdebug_values['install'] == 1 {
  class { 'xdebug':
    service => $xdebug_webserver_service
  }

  if is_hash($xdebug_values['settings']) and count($xdebug_values['settings']) > 0 {
    $xdebug_values['settings'].each { |$key, $value|
      xdebug::augeas { $key:
        value   => $value,
        service => $xdebug_webserver_service
      }
    }
  }
}

## Begin MySQL manifest

if $mysql_values == undef {
  $mysql_values = hiera('mysql', false)
}

if $php_values == undef {
  $php_values = hiera('php', false)
}

if $mysql_values['root_password'] {
  class { 'mysql::server':
    root_password => $mysql_values['root_password'],
  }

  if is_hash($mysql_values['databases']) and count($mysql_values['databases']) > 0 {
    create_resources(mysql_db, $mysql_values['databases'])
  }

  if is_hash($php_values) and ! defined(Php::Module['mysql']) {
    php::module { 'mysql': }
  }
}

define mysql_db (
  $user,
  $password,
  $host,
  $grant    = [],
  $sql_file = false
) {
  mysql::db { $name:
    user     => $user,
    password => $password,
    host     => $host,
    grant    => $grant
  }

  if $sql_file {
    $table = "${name}.*"

    exec{ "${name}-import":
      command     => "mysql ${name} < ${sql_file}",
      logoutput   => true,
      refreshonly => $refresh,
      require     => Database_grant["${user}@${host}/${table}"],
      subscribe   => Database[$name],
      onlyif      => "test -f ${sql_file}"
    }
  }
}

