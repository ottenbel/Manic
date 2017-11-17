<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Do the production seeding 
		self::SeedStatusRow("727fbd10-f582-11e6-8d90-b7ce614a8bf5", "In Progress", 0);
		self::SeedStatusRow("728126a0-f582-11e6-92e2-958261649644", "Complete", 1);
		self::SeedStatusRow("728198f0-f582-11e6-bd1b-a7750535a415", "Cancelled", 2);
		self::SeedStatusRow("7281e0b0-f582-11e6-a14d-a1b50c45303f", "Hiatus", 3);
    }
	
	private static function SeedStatusRow($id, $name, $priority)
	{
		$status = Status::where('id', '=', $id)->first();
		if ($status == null)
		{
			$status = new Status();
			$status->id = $id;
			$status->fill(['name' => $name, 'priority' => $priority]);
			$status->save();
		}
	}
}