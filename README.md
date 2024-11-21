# Wage Wizards
## HR Salary Management Software
## CS2300 Final Project

Installation Guide
1. Unzip or extract CS2300-Project to the Desktop
2. Install XAMPP 8.0.30 for Linux from the internet
3. Open a terminal
4. In the terminal enter the command: cd ~/Downloads
5. In the terminal enter the command: chmod +x xampp-linux-*-installer.run
6. In the terminal enter the command: sudo ./xampp-linux-*-installer.run
7. Go through the setup wizard and allow XAMPP to finish downloading
8. If the XAMPP GUI automatically shows up skip to step 11
9. In the terminal enter the command: cd /opt/lampp
10. In the terminal enter the command: sudo ./manager-linux-x64.run
11. In the XAMPP GUI navigate to the  “Manage Servers” page
12. Start MySQL Database and Apache Web Server so that their status is Running
13. Do not exit the original terminal. Open a new terminal
14. In the new terminal enter the command: sudo rm -rf /opt/lampp/htdocs/*
15. In the new terminal enter the command: sudo cp -r ~/Desktop/CS2300-Project/front_end/* /opt/lampp/htdocs/
16. Open a web browser and in the url enter localhost/phpmyadmin/
17. On the top navigation bar of phpmyadin click on Import
18. In the “File to import” box click on “Browse”
19. Navigate to ~/Desktop/CS2300-Project/back_end/ and click on hr_database.sql and open the hr_database.sql file
20. Scroll to the bottom of the import page of phpmyadmin and click the “Import” button
21. In the url type in localhost and enjoy the Wage Wizard Salary Management website

