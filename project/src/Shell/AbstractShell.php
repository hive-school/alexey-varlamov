<?php
namespace Shell;

abstract class AbstractShell
{
    /**
     * @var array - input arguments
     */
    protected $args = [];

    public function __construct()
    {
        $this->prepareParams();
        $this->_showHelp();
        $this->validate();
    }

    /**
     * Parse input arguments
     */
    protected function prepareParams()
    {
        $current = null;
        foreach ($_SERVER['argv'] as $arg) {
            if ($arg === 'index.php') {
                continue;
            }

            $match = array();
            if (preg_match('/^--([\w\d,]+)$/i', $arg, $match) ||
                preg_match('/^-([\w\d,]+)$/', $arg, $match)
            ) {
                $current = $match[1];
                $this->args[$current] = true;
            } else {
//                echo $arg . PHP_EOL;
                if ($current) {
                    $this->args[$current] = $arg;
                }
            }
        }
    }

    /**
     * Retrieve argument value by name
     *
     * @param string $name      - name of the argument
     * @return mixed $result    - argument value or false
     */
    protected function getArg($name)
    {
        $result = false;
        if (isset($this->args[$name])) {
            $result = $this->args[$name];
        }

        return $result;
    }

    /**
     * @return array - input args
     */
    protected function getArgs()
    {
        return $this->args;
    }

    /**
     * Show help if input argument is 'h' or 'help'
     */
    private function _showHelp()
    {
        if (isset($this->args['h']) || isset($this->args['help'])) {
            die($this->showHelp());
        }
    }

    /**
     * Show app help
     *
     * @return mixed
     */
    abstract protected function showHelp();

    /**
     * Validate input params
     *
     * @return bool - true or throw exception
     */
    abstract protected function validate();
}