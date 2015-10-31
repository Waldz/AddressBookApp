<?php

namespace Application\Cli;

/**
 * Command line goodies
 *
 * @package Application
 * @subpackage Model
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class Exec
{

    /**
     * Runs script and prints to standart outputs
     *
     * @param string $cmd
     */
    public static function execWithOutput($cmd) {
        $descriptors = array(
            0 => array('pipe', "r"),  // stdin is a pipe that the child will read from
            1 => STDOUT,  // stdout is a pipe that the child will write to
            2 => STDOUT // stderr is a file to write to
        );
        proc_close(proc_open($cmd, $descriptors, $pipes));
    }

}
