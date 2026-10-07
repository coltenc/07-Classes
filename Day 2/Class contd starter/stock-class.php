<?php
/*
 * Week 7 Day 2 - Encapsulation: a Stock that protects its price
 * Run from the terminal: php stock-class.php
 * This is yesterday's finished Stock class. Change it one STEP at a time,
 * reading the notes for each step first. Run the file after every step.
 */
declare(strict_types=1);

class Stock
{
    public static int $nextId = 1;
    public static int $id;

    public string $symbol;
    public string $company;
    public float $price;


    public function __construct(string $symbol, string $company, float $price)
    {
        $this->id = self::$nextId;
        self::$nextId++;

        $this->symbol = $symbol;
        $this->company = $company;
        $this->price = $price;
    }


    public function totalFor(int $shares): float
    {
        return $this->price * $shares;
    }

    public function stockInfo(): string
    {
        return "{$this->company} is priced at $" . number_format($this->price, 2);
    }
}


$apple = new Stock('AAPL', 'Apple Inc.', 500.00);
$amazon = new Stock('AMZN', 'Amazon.com Inc.', 250.00);

$stocks = [];
array_push($stocks, $apple, $amazon);


foreach ($stocks as $stock) {
    echo "#{$stock->id} {$stock->symbol}: " . $stock->stockInfo() . "\n";
}

echo 'Next ID will be ' . Stock::$nextId . "\n\n";

$portfolio = [];
$file = fopen(__DIR__ . '/stock.csv', 'r');

while (($row = fgetcsv($file)) !== false) {
    $portfolio[] = new Stock($row[0], $row[1], (float) $row[2]);
}

fclose($file);


foreach ($portfolio as $stock) {
    echo "#{$stock->id} {$stock->symbol}: " . $stock->stockInfo()
        . ' | 10 shares: $' . number_format($stock->totalFor(10), 2) . "\n";
}

/* ---------------------------------------------------------------------
 * STEP 1 NOTES: break the object
 * ---------------------------------------------------------------------
 * - Every property is public, so ANY code can change it to anything.
 * - Try setting $apple's price to a negative number, then display it.
 * - In Week 5 we validated form fields. Why doesn't any of that help here?
 */


/* ---------------------------------------------------------------------
 * STEP 2 NOTES: private
 * ---------------------------------------------------------------------
 * - private means only code INSIDE the class can read or change the property.
 * - Methods are inside the class, so they can still use $this->price.
 * - Code OUTSIDE the class gets:  Error: Cannot access private property
 *   That's the point: the object now controls its own data.
 * - Make the price private. Try changing it AND reading it from out here.
 */


/* ---------------------------------------------------------------------
 * STEP 3 NOTES: a setter
 * ---------------------------------------------------------------------
 * - A SETTER is a public method that is the ONLY way to change a private
 *   property from outside. Name it after the property: setPrice()
 * - Because every change goes through it, it can CHECK the new value first.
 *   Our rule: a price must be greater than zero.
 * - Don't echo an error inside the method. RETURN true (changed) or false
 *   (refused) with a : bool return type. The caller decides what to show:
 *       if ($object->setSomething($value)) { ... } else { ... }
 */


/* ---------------------------------------------------------------------
 * STEP 4 NOTES: try / catch
 * ---------------------------------------------------------------------
 * - With strict types, passing text where a method expects a float makes PHP
 *   throw a TypeError, and the script stops.
 * - try { risky code } catch (TypeError $e) { what to do instead }
 * - If the try block fails, PHP skips the rest of it and runs the catch block.
 *   The script keeps going after the catch.
 * - Same pattern as the DateTime warm-up, with a different error type.
 * - TypeError is a CLASS too, with the same getMessage() method:
 *   https://www.php.net/manual/en/class.typeerror.php
 */


/* ---------------------------------------------------------------------
 * STEP 5 NOTES: getters
 * ---------------------------------------------------------------------
 * - A GETTER is a public method that RETURNS a private value: getPrice()
 *   It gives read-only access. Outside code can see the price, not change it.
 * - A getter can also return several values at once as an associative array:
 *   getStock() with keys id, symbol, company, price.
 */


/* ---------------------------------------------------------------------
 * STEP 6 NOTES: toString, then __toString
 * ---------------------------------------------------------------------
 * - First write a normal method, toString(), that returns a one-line string
 *   describing the stock, and echo $apple->toString().
 * - Now try echo $apple; on its own. PHP can't turn an object into text.
 * - Rename the method to __toString() (TWO underscores, like __construct).
 *   It's a MAGIC method: PHP calls it for you whenever the object is used
 *   as a string. echo $apple; now works.
 */


/* ---------------------------------------------------------------------
 * STEP 7 NOTES: an array of objects
 * ---------------------------------------------------------------------
 * - Put $apple and $amazon in an array and loop through it.
 * - Inside the loop, echo $stock; uses __toString() for every object.
 */


/* ---------------------------------------------------------------------
 * STEP 8 NOTES: a static method that builds objects
 * ---------------------------------------------------------------------
 * - Yesterday the stock.csv loop had to know that column 2 is the price.
 * - Move that one line into the class as a STATIC method:
 *       public static function fromCsvRow(array $row): Stock
 * - static means it belongs to the CLASS. Call it on the class, not an object,
 *   because no Stock exists yet:   Stock::fromCsvRow($row)
 * - It RETURNS a new Stock built from the row. Now the loop doesn't need to
 *   know the column order at all.
 * - stock.csv columns:  0 = symbol, 1 = company, 2 = price
 */
