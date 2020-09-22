<?php
namespace App\Apollo\Console\Commands;

use App\Apollo\Modules\Order\ConfigOrderExpress;
use CjsConsole\Command;
use CjsConsole\Input\InputOption;

class Test extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'user test command';

	public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //['name_one', null, InputOption::VALUE_NONE, 'name_one', null],//接收报错
            ['name_two', null, InputOption::VALUE_REQUIRED, 'name_two', null],
            ['name_three', null, InputOption::VALUE_OPTIONAL, 'name_three', null],
        ];
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
	    try{
	        var_export(ConfigOrderExpress::getConfig());
	        var_export(ConfigOrderExpress::getExpressList());
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
	}

}
