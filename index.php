<?php
declare(strict_types =1);

/**
 * CLI application main class
 */
class CLIApp {
    private FinanceManager $financeManager;

    public const ADD_INCOME = 1;
    public const ADD_EXPENSE = 2;
    public const VIEW_INCOME = 3;
    public const VIEW_EXPENSE = 4;
    public const VIEW_SAVINGS = 5;
    public const VIEW_CATEGORIES = 6;
    public const EXIT_APP = 7;
    public array $options = [
        self::ADD_INCOME        => 'Add Income',
        self::ADD_EXPENSE       => 'Add Expense',
        self::VIEW_INCOME       => 'View Income',
        self::VIEW_EXPENSE      => 'View Expense',
        self::VIEW_SAVINGS      => 'View Savings',
        self::VIEW_CATEGORIES   => 'View Categories',
        self::EXIT_APP          => 'Exit'
    ];
    public const CATEGORIES = [
        'Name: Salary, Type: INCOME',
        'Name: Business, Type: INCOME',
        'Name: Loan, Type: INCOME',
        'Name: Rent, Type: EXPENSE',
        'Name: Family, Type: EXPENSE',
        'Name: Recreation, Type: EXPENSE',
        'Name: Sadakah, Type: EXPENSE',
        'Name: Food, Type: EXPENSE',
    ];

    /**
     * Constructor class
     */
    public function __construct()
    {
        $this->financeManager = new FinanceManager();
    }

    /**
     * To run CLIApp, call this function
     *
     * @return void
     */
    public function run(): void
    {
        while (true) {
            foreach( $this->options as $option => $label ) {
                printf("%d %s\n", $option, $label);
            }

            $choice = (int) readline(prompt: "Enter your option: ");

            switch ($choice) {
                case self::ADD_INCOME: 
                    $amount = (float) trim( readline(prompt: "Enter income amount: ") );
                    $category = readline(prompt: "Enter income category: ");
                    $this->financeManager->addIncome($amount, $category);
                    break;
                case self::ADD_EXPENSE: 
                    $amount = (float) trim( readline(prompt: "Enter expense amount: ") );
                    $category = readline(prompt: "Enter expense category: ");
                    $this->financeManager->addExpense($amount, $category);
                    break;
                case self::VIEW_INCOME: 
                    $this->financeManager->showIncomes();
                    break;
                case self:: VIEW_EXPENSE: 
                    $this->financeManager->showExpenses();
                    break;
                case self::VIEW_SAVINGS: 
                    $this->financeManager->showSavings();
                    break;
                case self::VIEW_CATEGORIES: 
                    $this->financeManager->showCategories();
                    break;
                case self::EXIT_APP:
                    echo "Thanks for using our App. \n\n";
                    return;
                default:
                    echo "Invalid option. \n";
                    echo ".................. \n";
                    break;
            }
        } 
    }
}

$app = new CLIApp;
$app->run();

/**
 * Financial manager helper class
 * 
 * It stores, calculate and store nall our finanacial transcation
 */
class FinanceManager {

    // use Display;
    private $filename = 'file.txt';
    public function __construct()
    {
        // if file not found, then create it!
        if ( !file_exists( $this->filename ) ) {
            file_put_contents( $this->filename, '', FILE_USE_INCLUDE_PATH );
        }
        // echo "Someone called me! \n";
    }
/**
 * Add income to a file
 *
 * @param float $amount
 * @param string $category
 * @return void
 */
    public function addIncome($amount, $category) {
        // first read the file and then add amount to that file and save it.
        $file_contents = file_get_contents($this->filename);
        $file_contents = unserialize($file_contents);
        if ( !empty( $file_contents ) ) {
            $income_data = $file_contents[CLIApp::ADD_INCOME];
            if ( ! empty($income_data) ) {
                array_push($income_data, $amount);
                $file_contents[CLIApp::ADD_INCOME] = $income_data;
                // print_r( $income_data );
                file_put_contents($this->filename, serialize($file_contents));   
            } else {
                // echo "---------- inside income --------- \n";
                // print_r( $file_contents );
                $file_contents += [
                    CLIApp::ADD_INCOME => [$amount]
                ];
                file_put_contents($this->filename, serialize($file_contents));                
            }    
        } else {
            $file_contents = [
                CLIApp::ADD_INCOME => [$amount]
            ];
            file_put_contents($this->filename, serialize($file_contents));
        }
    }
    
    /**
     * Read file data and diplay income as a list. 
     *
     * @return void
     */
    public function showIncomes() {
        // first read the file and then add amount to that file and save it.
        $file_content = file_get_contents($this->filename) ;
        $file_content = unserialize( $file_content );

        if ( empty ($file_content[CLIApp::ADD_INCOME]) ) {
            echo "No data found \n\n";
            echo ".................. \n\n";
            return;
        }

        echo "------------- \n";
        foreach( $file_content[CLIApp::ADD_INCOME] as $income ){
            echo "Amount: $income \n";            
        }
        echo "------------- \n\n";
    }

    /**
     * Read data from a file and display expense as a list
     *
     * @return void
     */
    public function showExpenses() {
        // first read the file and then add amount to that file and save it.
        $file_content = file_get_contents($this->filename) ;
        $file_content = unserialize( $file_content );

        if ( empty ($file_content[CLIApp::ADD_EXPENSE]) ) {
            echo "No data found \n\n";
            echo ".................. \n\n";
            return;
        }

        echo "------------- \n";
        foreach( $file_content[CLIApp::ADD_EXPENSE] as $expense ){
            echo "Amount: $expense \n";            
        }
        echo "------------- \n\n";
    }

    /**
     * Adding expense to a give file
     *
     * @param int|float $amount
     * @param string $category
     * @return void
     */
    public function addExpense($amount, $category): void
    {        
        $file_contents = file_get_contents($this->filename);
        $file_contents = unserialize($file_contents);
        if ( !empty( $file_contents ) ) {
            // print_r( $file_contents );
            $expense_data = isset($file_contents[CLIApp::ADD_EXPENSE]) ? $file_contents[CLIApp::ADD_EXPENSE] : [];
            if ( ! empty($expense_data) ) {
                array_push($expense_data, $amount);
                $file_contents[CLIApp::ADD_EXPENSE] = $expense_data;
                // print_r( $expense_data );
                file_put_contents($this->filename, serialize($file_contents));   
            } else {
                // echo "---------- inside expense --------- \n";
                // print_r( $file_contents );
                $file_contents += [
                    CLIApp::ADD_EXPENSE => [$amount]
                ];
                file_put_contents($this->filename, serialize($file_contents));                
            }
        } else {
            $file_contents = [
                CLIApp::ADD_EXPENSE => [$amount]
            ];
            file_put_contents($this->filename, serialize($file_contents));
        }
    }

    /**
     * Read income and expense data from a file and Display total savings
     *
     * @return void
     */
    public function showSavings(): void
    {
        $file_contents = file_get_contents($this->filename);
        $file_contents = unserialize($file_contents);
        $income = isset( $file_contents[CLIApp::ADD_INCOME] ) ? $file_contents[CLIApp::ADD_INCOME] :  ['0' => 0];
        $savings = isset( $file_contents[CLIApp::ADD_EXPENSE] ) ? $file_contents[CLIApp::ADD_EXPENSE] :  ['0' => 0];
        $savings = array_sum($income) - array_sum($savings);
        echo "----------------------- \n";
        echo "Savings: $savings \n";
        echo "----------------------- \n\n";
    }

    /**
     * Display list of categories
     *
     * @return void
     */
    public function showCategories(): void {
        echo "-------------------------\n";
        foreach( CLIApp::CATEGORIES as $category ) {
            echo $category . "\n";
        }
        echo "-------------------------\n\n";
    }
}