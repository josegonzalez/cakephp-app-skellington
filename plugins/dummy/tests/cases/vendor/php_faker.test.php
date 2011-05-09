<?php
/**
 * Test / examples of all the current Vendor generators
 * 
 * @author Alexander Morland (alkemann)
 * @modified 10. feb. 2009
 */
class PhpFakerCase extends CakeTestCase {
	private $Faker = null;
	
	public function start() {
		App::import('vendor','Dummy.faker');
		$this->Faker = new Faker;
	}
	/**/
	public function testEnglish() {
		$E = &$this->Faker->English;
		$a['title'] = $E->title;
		$a['noun'] = $E->noun;
		$a['verb'] = $E->verb;
		$a['quote'] = $E->quote;
		$a['extension'] = $E->extension;
		$a['filename'] = $E->filename;
		$a['color'] = $E->color;
		$a['city'] = $E->city;
		
		debug(array('English' => $a));
	}
	/**/
	public function testNumber() {
		$N = &$this->Faker->Number;
		$a['integer']['full'] = $N->integer;
		$a['integer']['0 12'] = $N->integer(array('min'=>0,'max'=>12));
		$a['integer']['max 12'] = $N->integer(array('max'=>12));
		$a['integer']['min 12'] = $N->integer(array('min'=>12));
		
		$a['float']['full'] = $N->float;
		$a['float']['0 12'] = $N->float(array('min'=>1,'max'=>12));
		$a['float']['%01.2f'] = $N->float(array('variable'=>'%01.2f'));
		
		$a['bigInt'] = $N->bigInt;
		$a['mediumInt'] = $N->mediumInt;
		$a['smallInt'] = $N->smallInt;
		$a['tinyInt']['full'] = $N->tinyInt;
		$a['tinyInt']['tinyInt(1)'] = $N->tinyInt(array('max'=>1));
		
		$a['boolean'] = $N->boolean;
				
		debug(array('Number' => $a));
	}
	/**/
	public function testTime() {
		$T = &$this->Faker->Time;
		
		$a['date']['full'] = $T->date;
		$a['date']['now'] = $T->date(array('variable' => 'now'));
		$a['date']['future'] = $T->date(array('variable' => 'future'));
		$a['date']['future next week'] = $T->date(array('variable' => 'future','max' => 'next week'));
		$a['date']['future 2038'] = $T->date(array('variable' => 'future','max' => '2038-01-01'));
		$a['date']['past'] = $T->date(array('variable' => 'past'));
		$a['date']['past last week'] = $T->date(array('variable' => 'past','min' => 'last week'));
		
		$a['datetime']['full'] = $T->datetime;
		$a['datetime']['+-2days'] = $T->datetime(array('min'=>'-2days','max'=>'+2days'));
		$a['datetime']['now'] = $T->datetime(array('variable' => 'now'));
		
		$a['time']['full'] = $T->time;
		$a['time']['+1hour'] = $T->time(array('min'=>'now','max'=>'+1hour'));
		$a['time']['-2hours'] = $T->time(array('min'=>'-2hours','max'=>'now'));
		
		$a['timestamp'] = $T->timestamp;
		
		$a['year']['default'] = $T->year;
		$a['year']['my life'] = $T->year(array('min' => 1979, 'max' => 2008));
		$a['year']['now'] = $T->year(array('variable' => 'now'));
		$a['year']['past'] = $T->year(array('variable' => 'past'));
		$a['year']['future'] = $T->year(array('variable' => 'future', 'max' => 2030));
		
		$a['month']['default'] = $T->month;
		$a['month']['now'] = $T->month(array('variable' => 'now'));
		$a['month']['past'] = $T->month(array('variable' => 'past'));
		$a['month']['future'] = $T->month(array('variable' => 'future'));
		
		$a['day']['default'] = $T->day;
		$a['day']['max 31'] = $T->day(array('max'=>31));
		$a['day']['now'] = $T->day(array('variable' => 'now'));
		$a['day']['past'] = $T->day(array('variable' => 'past'));
		$a['day']['future'] = $T->day(array('variable' => 'future'));
		
		debug(array('Time' => $a));
	}	
	/**/
	public function testWeb() {
		$W = &$this->Faker->Web;
		
		$a['domain']['suffix'] = $W->domain_suffix;
		$a['domain']['word'] = $W->domain_word;
		$a['domain']['name'] = $W->domain_name;
		
		$a['email']['default'] = $W->email;
		$a['email']['alexander,morland'] = $W->email(array('variable'=>array('alexander','morland')));
		$a['email']['a,b,c,d'] = $W->email(array('variable'=>array('a','b','c','d')));
		$a['email']['alexander morland'] = $W->email(array('variable'=>'alexander morland'));
				
		$a['username']['default'] = $W->username;
		$a['username']['Jonny'] = $W->username(array('variable'=>'Jonny'));
		$a['username']['Alexander Morland'] = $W->username(array('variable'=>'Alexander Morland'));
		
		$a['password'] = $W->password;
		
		$a['url'] = $W->url;
		
		$a['html'] = $W->html;
		
		debug(array('Web' => $a),true);
	}
	/**/
	public function testName() {
		$N = &$this->Faker->Name;
		
		$a['first_name']['default'] = $N->first_name;
		$a['first_name']['single'] = $N->first_name(array('single' => true));
		$a['first_name']['var single'] = $N->first_name(array('variable' => 'single'));
		$a['first_name']['max 25'] = $N->first_name(array('max' => 25));
		
		$a['surname']['default'] = $N->surname;
		$a['surname']['single'] = $N->surname(array('single' => true));
		$a['surname']['var single'] = $N->surname(array('variable' => 'single'));
		
		$a['prefix'] = $N->prefix;
		$a['suffix'] = $N->suffix;
		
		$a['name'] = $N->name;		
		$a['full_name'] = $N->full_name;
		
		debug(array('Name' => $a),true);
	}
	/**/
	public function testAddress() {
		$Ad = &$this->Faker->Address;
		
		$a['street_suffix'] = $Ad->street_suffix;
		$a['street_name'] = $Ad->street_name;
		$a['street_address'] = $Ad->street_address;
		$a['abode_address'] = $Ad->abode_address;
		
		$a['zip_code']['default'] = $Ad->zip_code;
		$a['zip_code']['5xXX'] = $Ad->zip_code(array('variable' => '5xXX'));
		
		$a['post_code']['default'] = $Ad->post_code;
		$a['post_code']['5xXX'] = $Ad->post_code(array('variable' => '5xXX'));
		
		$a['phone']['default'] = $Ad->phone;
		$a['phone']['555 Xxx xxx'] = $Ad->phone(array('variable'=>'555 Xxx xxx'));
		
		debug(array('Address' => $a),true);
	}
	/**/
	public function testUsa() {
		$Usa = &$this->Faker->Usa;
		
		$a['us_state'] = $Usa->us_state;
		$a['us_state_abbr'] = $Usa->us_state_abbr;
		$a['zip_code'] = $Usa->zip_code;
		
		debug(array('Usa' => $a),true);
	}
	/**/
	public function testUk() {
		$Uk = &$this->Faker->Uk;
		
		$a['uk_county'] = $Uk->uk_county;
		$a['uk_country'] = $Uk->uk_country;
		$a['post_code'] = $Uk->post_code;
		
		debug(array('Uk' => $a),true);
	}
	/**/
	function testLorem() {
		$L = $this->Faker->Lorem;
		
		$a['word'] = $L->word;
		$a['sentence'] = $L->sentence(array(''));
		$a['paragraph'] = $L->paragraph;
		
		debug(array('Lorem' => $a),true);		
		
		App::import('vendor','Dummy.DummyWrapper');
		$english_generators = DummyWrapper::listMethods('Lorem');
		debug(array(
			'code' => 'DummyWrapper::listMethods("Lorem")',
			'english_generators' => $english_generators));
	}	
	/**/
	function testCompany() {
		$C =& $this->Faker->Company;
		
		$a['name'] = $C->name;
		$a['suffix'] = $C->suffix;
		$a['catch_phrase'] = $C->catch_phrase;
		$a['bs'] = $C->bs;
		
		debug(array('Company' => $a));
	}
	/**/
	function testDummyWrapper() {
		App::import('vendor','Dummy.DummyWrapper');
		
		$classes = DummyWrapper::listClasses(false);
		debug(array(
			'code' => 'DummyWrapper::listClasses(false)',
			'classes' => $classes));		
				
		$english_generators = DummyWrapper::listMethods('English');
		debug(array(
			'code' => 'DummyWrapper::listMethods("English")',
			'english_generators' => $english_generators));
		
		$name = DummyWrapper::generate('English','title', array('max' => 200));
		debug(array(
			'code' => 'DummyWrapper::generate("English","title", array("max" => 200))',
			'english title' => $name));
				
		$subclasses = DummyWrapper::listSubClasses('Address');
		debug(array(
			'code' => 'DummyWrapper::listSubClasses("Address")',
			'classes' => $subclasses));		
		
		$uk_post_code = DummyWrapper::generate('Uk','post_code');
		debug(array(
			'code' => 'DummyWrapper::generate(\'Uk\',\'post_code\')',
			'post_code' => $uk_post_code));		
		
		$us_post_code = DummyWrapper::generate('Usa','post_code');
		debug(array(
			'code' => 'DummyWrapper::generate(\'Usa\',\'post_code\')',
			'post_code' => $us_post_code));		
	}
	/**/
}
?>