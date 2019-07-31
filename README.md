# ConfigWriter

ConfigWriter allows you to modify or create config files easily.
It was written to change templates e.g. '.env.example'.

## Usage

The constructor of ConfigWriter requires the filename of the
existing or to be created config file and optionally the array
of settings, the delimiter of the key value pair and at least the
comment chars of the file (only full line comments are supported.

The options array holds a nested array for each configuration
line which consits of section, key and value. If your
configuration file doesn't support sections set it to null.

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
