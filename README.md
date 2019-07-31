# ConfigWriter

ConfigWriter is a PHP library which allows you to modify or
create config files easily.
It was written to change templates e.g. '.env.example' and to
keep the formatting and order of the original file as far as
possible.

## Usage

The constructor of ConfigWriter requires the filename of the
existing or to be created config file and optionally the array
of settings, the delimiter of the key value pair and at last the
comment chars of the file.

The options array holds a nested array for each configuration
line. The nested array consits of three elements: section, key and
value. If your configuration file doesn't support sections set
it to null.

    $cw = new ConfigWriter(
        './test.ini',
        [
            [ 'section3', 'opt1', 'newvalue' ],
            [ 'section4', 'opt2', 'newvalue' ],
        ]
    );
    $cw->write();

To write a ssh-config style file:

    $cw = new ConfigWriter('./ssh.conf', [], ' ', '#');
    $cw->write(
        [
            [ null, 'Port', '22' ],
            [ null, 'PermitRootLogin', 'no' ],
        ]
    );

The write method will return true if the file had to be modified.

### Comments

If an option was commented ConfigWriter will reuse this line to
set the option to a new value. Only full line comments are supported.

You can specify the chars to detect a commented line at construction
time, see above. The default value is '#;'.

## Shell based alternative

If you need to manage configuration files at a lower level e.g. to
modify the .env file the following script could be of use to you.
You can find the awk programme file in contrib/awk.

    #!/bin/bash

    declare -A env

    env[APP_ENV]="testing"
    env[APP_URL]="myapp"
    env[DB_DATABASE]="db"

    for key in ${!env[@]}; do
        awk -f contrib/awk/set-option-to-value -v option=${key} \
	    -v value=${env[${key}]} .env > .env.tmp
        mv .env.tmp .env
    done
