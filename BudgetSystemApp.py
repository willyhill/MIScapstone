from tkinter import *
from tkinter import messagebox
from tkinter import simpledialog

'''
William Li

Test Case 1: Successful Setup / Login
Description: 
        The test verifies the functionality of the program
        when a user successfully sets their password, and logs in
        to the application and sets a budget goal and pick options.
Step-by-step procedure for the user: 
        1. run the program
        2. choose setup password by typing 'S'
        3. select 'Y' or 'N' if you are sure to set up new password
        4. enter new password
        5. choose log in by typing 'L'
        6. enter correct password
        7. after successful login, set budget goal
        8. after budget goal is set proceed with the application
        9. enter a number 1 through 7 based on the choice.
Conditions Tested: 
        - correctness of login in process
        - proper storage and retrieval of the password
        - proper display of application if login is successful or not
        - ability to set budget goal
Expected result: 
        - the program should display "login successful" after correct password is entered.
        - after setting the budget goal, program should display the number you entered.
Final result: 
        - the test case ensures that login process and setting of budget goal works.
        - it verifies that the program correctly stores and retrieves the password for security.
        - it confirms that the user can set a budget goal without any issues.
        - it ensures that the application doesnt appear if users enter wrong password 3 times.


Test Case 2: Adding expenses / viewing expenses by category
Description: 
        This test case verifies the functionality of viewing expenses by category.
Step-by-step procedure for the user: 
        1. After successfully logging in and setting budget goal, type '2' to add expenses
        2. First enter category of expense. Ex: car
        3. Second enter expense amount. Ex: 2000
        4. type '5' to view expenses by category
Conditions Tested: 
        - proper retrieval of expenses by category.
        - display expenses categorized correctly.
        - if there is nothing added to expenses, print "nothing added to expenses"
        - if category is a number, display error and break
Expected result: 
        - After selecting add expenses you will be prompt to enter category, and expense amount.
        - when finished selecting view expenses by category should display a list of expenses categorized
            with their category and amount entered.
Final result: 
        - The test case ensures the program correctly retrieves and displays expenses categorized by their catergory.
        - it verifies that the program accurately presents the user with a breakdown of expenses, for better financial management.
'''

def create_password(): 
    while True:
        password_choice = input("\nYou selected set a password correct? (Y) or (N) ").upper()
        password = []
        
        if password_choice == 'Y':
            password = input("Enter your password: ")

            with open("password.txt", "w") as file: #stores password in file only for 1 user.
                file.write(password)

            print("Password successfully created:", password)
            return password
        elif password_choice == 'N':
            no = print("No password was set.")
            return no          
        else:
            print("Invalid choice. Please enter either 'Y' or 'N'.")

def login():
    stored_password = None
    try:
        with open("password.txt", "r") as file: #reads the stored password from the file
            stored_password = file.read()
    except FileNotFoundError:
        print("No password set yet.")
        return False

    if stored_password:
        attempts = 3
        while attempts > 0:
            entered_password = input("Enter your password to log in: ")
            if entered_password == stored_password:
                print("Login successful.")
                return True
            else:
                attempts -= 1
                print("Incorrect password. Please try again.")
        print("Too many incorrect attempts. Exiting.")
        return False
    else:
        print("No password set yet.")
        return False

def setBudgetGoal():
    while True:
        try:
            userBudgetGoal = float(input("Please enter your budget goal: "))
            if userBudgetGoal <= 0:
                print("Budget goal must be a positive number.")
            else:
                with open("budget_goal.txt", "w") as file:
                    file.write(str(userBudgetGoal))
                print("Budget goal set to:", userBudgetGoal)
                return userBudgetGoal
        except ValueError:
            print("Invalid input. Please enter a valid number.")

def getBudgetGoal():
    try:
        with open("budget_goal.txt", "r") as file:
            return float(file.read().strip()) 
    except FileNotFoundError:
        print("Budget goal not set yet.")
        return None


class BudgetApplication:
    def __init__(self):
        self.defIncome = 0
        self.defExpenses = {}

    def add_income(self, income):
        self.defIncome += income

    def add_expense(self, category, amount):
        if category in self.defExpenses:
            self.defExpenses[category] += amount
        else:
            self.defExpenses[category] = amount

    def getTotalExpenses(self):
        return sum(self.defExpenses.values())

    def getBalance(self):
        return self.defIncome - self.getTotalExpenses()

    def getExpensesByCategory(self):
        return self.defExpenses

    def resetBudget(self):
        self.defIncome = 0
        self.defExpenses = {}

def main():

    while True:
        app = BudgetApplication()

        choice = input("Would you like to (S)et a password, (L)og in, or (Q)uit? ").upper()
        
        if choice == 'S':
            create_password()
        elif choice == 'L':
            if login():
                setBudgetGoal()
                while True:
                    print("\nBudget System Application")
                    print("(1) Add Income")
                    print("(2) Add Expenses")
                    print("(3) View Total Expenses")
                    print("(4) View Balance")
                    print("(5) View Expenses by Category")
                    print("(6) Reset Budget")
                    print("(7) Quit")

                    choice = input("Enter your choice: ")

                    if choice == '1':
                        income = float(input("Enter Income Amount: "))
                        app.add_income(income)
                        print("Income Added Successfully.")

                    elif choice == '2':
                        category = input("Enter Expense Category: ")
                        if category.isdigit():
                            print("Error! Category cannot be a number. Please retry.")
                            break
                        amount = float(input("Enter Expense Amount: "))
                        app.add_expense(category, amount)
                        print("Expense Added Successfully.")

                    elif choice == '3':
                        totalExpenses = app.getTotalExpenses()
                        print("Total Expenses: ", totalExpenses)

                    elif choice == '4':
                        balance = app.getBalance()
                        print("Balance: ", balance)
                        budget_goal = getBudgetGoal()
                        if balance > budget_goal:
                            print("Above budget goal of", budget_goal)
                        elif balance < budget_goal:
                            print("Below budget goal of", budget_goal)
                        else:
                            print("Budget goal matches!", budget_goal)

                    elif choice == '5':
                        expensesByCategory = app.getExpensesByCategory()
                        if expensesByCategory:
                            print("Expenses by category: ")
                        else:
                            print("Nothing added in expenses")
                        for category, amount in expensesByCategory.items():
                            print(category + ": ", amount)

                    elif choice == '6':
                        app.resetBudget()
                        print("Budget reset sucessfully.")

                    elif choice == '7':
                        print("Goodbye!")
                        break
                    else:
                        print("invalid choice, please try again.")
            else:
                break
        elif choice == 'Q':
            print("Exiting program.")
            break
        else:
            print("Invalid choice. Please enter 'S', 'L', or 'Q'.")

if __name__ == "__main__":
    main()