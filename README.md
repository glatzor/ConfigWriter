<?php

namespace IniWriter;

/**
 * Class IniWriter
 *
 * @package IniWriter
 *
 * Config the sentry integration
 */
class IniWriter
{
    /**
     * IniWriter constructor.
     *
     * @param string $filename
     * @param array  $options
     * @param string $delimeter
     * @param string $comment
     */
    public function __construct(
        string $filename,
        array $options = [],
        string $delimiter = "=",
        string $comment = "#;"
    )
    {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->comment = $comment;
        $this->options = $options;
    }

        $this->setIniOptions(
            __DIR__ . '/../../../../.sentryclirc',
            [
                [ 'auth', 'token', static::AUTH_TOKEN ],
                [ 'defaults', 'project', $project],
            ]
        );

        $this->setIniOptions(
            __DIR__ . '/../../../../.env',
            [
                [ null, 'SENTRY_LARAVEL_DSN', static::ACCOUNTS[$account]['backend'] ],
                [ null, 'SENTRY_VUE_DSN', static::ACCOUNTS[$account]['frontend'] ],
                [ null, 'SENTRY_PROJECT', $project ],
                [ null, 'SENTRY_AUTH_TOKEN', static::AUTH_TOKEN ],
            ]
        );

        return;
    }

    /**
     * Set the options and write the INI file.
     *
     * Returns true if the file had to be modified.
     *
     * @param array $options
     *
     * @return bool
     *
     * @throws FileNotFoundException
     */
 
    public function write(array $options = [])
    {
        if ($options != []) {
            $this->options = $options;
        }

        $changed = false;
        $array = explode("\n", file_get_contents($this->$filename));
        foreach ($this->options as list($section, $option, $value)) {
            $changed = $this->setIniArrayOption($array, $section, $option, $value) || $changed;
        }
        if ($changed == true) {
            file_put_contents($filename . '.tmp', implode(PHP_EOL, $array));
            rename($filename . '.tmp', $filename);
        }
        return changed;
    }

    private function setIniArrayOption(&$config, $section, $option, $value)
    {
        $newline = $option . $this->delimiter . $value;
        $configSearch = $config;

        # If a section was specified we only have to search the option
        # in the section, so reduce the conifg array
        if ($section != null) {
            $configSearch = $this->getSectionArray($config, $section);
            if ($configSearch == []) {
                # The section doesn't yet exists, so just append it
                $config[] = '[' . $section . ']';
                $config[] = $newline;
                return true;
            }
        }

        # Search every line for the option
        foreach ($configSearch as $index => $line) {
            if (preg_match(
                    '/^[' . $this->comment . ']?' . $option . '\s*' . $this->delimiter .'/',
                    $line
                )  == 1)
            {
                if ($line == $newline) {
                    return false;
                } else {
                    $config[$index] = $option . $this->delimiter . $value;
                    return true;
                }
            }
        }
        # We haven't found it, so just append and return
        if ($section == null) {
            $config[] = $newline;
        } else {
            # Insert the the new line directly after the section header
            array_splice($config, array_keys($configSearch)[0], 0, $newline);
        }
        return true;
    }

    private function getSectionArray($config, $section)
    {
        $sub = [];
        $found = false;
        foreach ($config as $index => $line) {
            if (preg_match('/^\[' . $section . '\]\s*$/', $line) == 1) {
                # We found the section beginning
                $found = true;
            } elseif ((preg_match('/^\[[a-zA-Z0-9]+\]\s*$/', $line) == 1) && ($found)) {
                # We found a second section
                return $sub;
            } elseif ($found) {
                # We found a line belonging to the section
                $sub[$index] = $line;
            }
        }
        return $sub;
    }
}
