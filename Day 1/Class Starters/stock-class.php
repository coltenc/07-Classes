<?php
declare(strict_types=1);
/*
 * Week 7 Day 1 - Build a Stock class
 * Run from the terminal: php stock-class.php
 * Work through one STEP at a time and run the file after each one.
 * Add your code BELOW each set of notes.
 */

/* ---------------------------------------------------------------------
 * STEP 3 NOTES: strict types   (it lives up here, but we add it in Step 3)
 * ---------------------------------------------------------------------
 * - PHP normally CONVERTS a value to fit a declared type: the int 2026 quietly
 *   becomes the string "2026". That hides mistakes.
 * - declare(strict_types=1); tells PHP: don't convert, throw a TypeError instead.
 * - It must be the FIRST statement after <?php (comments don't count).
 * - From now on, every class file starts with it.
 */


/* ---------------------------------------------------------------------
 * STEP 2 NOTES: classes, objects, and properties
 * ---------------------------------------------------------------------
 * - A CLASS is a blueprint. You write it once.
 *   An OBJECT is one thing built from that blueprint. You can build many.
 * - PROPERTIES are the variables inside a class: what every object HAS.
 *   Give each one an access level and a type:   public string $name;
 *     public  = code outside the class can read and change it
 *     types   = int, float, string, bool
 * - Class names use PascalCase (Stock, DateTime). Properties use camelCase.
 * - "new ClassName()" builds an object and gives it back to a variable.
 * - The OBJECT OPERATOR -> reaches inside an object:  $object->property
 *   No $ after the arrow:  $item->name   (not $item->$name)
 * - Our Stock needs: id (int), symbol (string), company (string), price (float).
 */
class Stock{
    public int $id;
    public string $symbol;
    public string $company;
    public float $price;


    public function __construct(string $symbol, string $company, float $price){
        echo("In am a new object");

        $this->symbol = $symbol;
        $this->company = $company;
        $this->price = $price;
    }


    public function totalFor(int $shares): float{
        return $this->price * $shares;
    }

    public function stockInfo(): string{
        return"{$this->company} is priced at $" . number_format($this->price, 2);
    }
}

$apple = new Stock("AAPL", "Apple Inc.", 500.00);
// $apple->id = 1;
// $apple->symbol = "AAPL";
// $apple->company = "Apple Inc.";
// $apple->price = 500.00;

echo  '10 shares: $' . number_format($apple->totalFor(10), 2);
echo ($apple->stockInfo());


$amazon = new Stock('AMZN', 'Amazon.com Inc.', 250.00);
// $amazon->id = 2;
// $amazon->symbol = 'AMZN';
// $amazon->company = 'Amazon.com Inc.';
// $amazon->price = 250.00;
 
$stocks = [];
array_push($stocks, $apple, $amazon);
 
foreach ($stocks as $stock) {
    echo $stock->stockInfo() . "\n";
}

/* ---------------------------------------------------------------------
 * STEP 4 NOTES: methods
 * ---------------------------------------------------------------------
 * - A METHOD is a function that lives inside a class: what an object can DO.
 *     public function methodName(type $parameter): returnType { ... }
 * - $this means "the object this method was called on."
 *   Inside a method, read a property with $this->property.
 *   A plain $price inside a method is a brand-new, empty local variable.
 * - Call a method through an object:  $object->methodName(arguments)
 *   Without the object in front, PHP looks for a regular function and fails.
 * - The RETURN TYPE after the colon (: float, : string) is a promise about
 *   what comes back.
 * - RETURN values instead of echoing them. The caller decides where the value
 *   goes (the terminal, a table cell, a calculation).
 * - number_format($number, 2) returns a STRING with 2 decimal places.
 *   (Look it up on php.net and check its return value.)
 * - An array can hold objects. foreach works the same way, and each item
 *   still has its properties and methods.
 * - A class can have only ONE method with a given name. No overloading.
 */


/* ---------------------------------------------------------------------
 * STEP 5 NOTES: the constructor and static properties
 * ---------------------------------------------------------------------
 * - The CONSTRUCTOR is a method named __construct (TWO underscores).
 *   PHP runs it automatically when "new" is called.
 * - Values in new ClassName(...) are passed to the constructor's parameters.
 *   The constructor copies them into properties:  $this->name = $name;
 *   ($this->name is the property; $name is the parameter.)
 * - Once a class has a constructor with parameters, new ClassName() with no
 *   arguments fails. An object can't exist half-built.
 * - A class has only ONE constructor.
 *
 * - A STATIC property belongs to the CLASS, not to each object.
 *   There is ONE copy, shared by every object:   public static int $nextId = 1;
 * - $this points to ONE OBJECT.  self points to THE CLASS.
 *     inside the class:   self::$nextId
 *     outside the class:  ClassName::$nextId
 *   :: is the scope resolution operator. It reaches into a class the way
 *   -> reaches into an object. Keep the $ after :: for static properties.
 * - A static counter resets every time the script runs. PHP forgets
 *   everything when the script ends. (A database will remember for us later.)
 */


/* ---------------------------------------------------------------------
 * STEP 6 NOTES: CSV rows become objects
 * ---------------------------------------------------------------------
 * - stock.csv columns:  0 = symbol, 1 = company, 2 = price
 * - Read it with the same fopen / fgetcsv / fclose loop from Week 6.
 * - Every value from a CSV is TEXT. With strict types on, convert the price
 *   yourself with (float).
 * - Only ONE line should know the column numbers: the line that builds the
 *   object. Everything after it uses property names.
 */
$portfolio = [];
$file = fopen(__dir__ . "/stock.csv", "r");

while(($row = fgetcsv($file)) !== false){
    $portfolio[] = new Stock($row[0], $row[1], (float)$row[2]);
}
fclose($file);

foreach($portfolio as $stock){    
    echo "{$stock->symbol}: " . $stock->stockInfo() . ' | 10 shares: $' . number_format($stock->totalFor(10), 2) . "\n";
}