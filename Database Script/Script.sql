-- ASHNA Database
CREATE DATABASE ASHNA;

-- 1: PERSONS/CUSTOMER(ASHKHAS)

    -- Company table
    CREATE TABLE company(
        company_id int AUTO_INCREMENT ,
        company_name varchar(128) not null,
        legal_name varchar(128) not null,
        company_type varchar(128) not null,
        license_number varchar(64) null,
        TIN varchar(64) null,
        register_number varchar(64) null,
        country varchar(64) null,
        province varchar(64) null,
        district varchar(64) null,
        postal_code varchar(64) null,
        phone varchar(64) null,
        fax varchar(64) null,
        addres varchar(64) null,
        website varchar(64) null,
        email varchar(64) null,   
        createby int ,
        updatedby int,
        PRIMARY key(company_id),
        CONSTRAINT company_created FOREIGN KEY(createby) REFERENCES users(user_id),
        CONSTRAINT company_updated FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );

    -- all users data that can access the system
    CREATE TABLE users(
        user_id int AUTO_INCREMENT ,
        company_id int not null,
        username varchar(128) not null,
        password varchar(128) not null,
        user_status varchar(16) not null,
        is_online varchar(8),
        createby int,
        updatedby int,
        PRIMARY key(user_id),
        FOREIGN key(company_id) REFERENCES company(company_id),
        CONSTRAINT user_created FOREIGN KEY(createby) REFERENCES users(user_id),
        CONSTRAINT user_updated FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );

    -- users roles
    CREATE TABLE user_cruid_role(
        user_roll_id int AUTO_INCREMENT ,
        user_id int,
        tble varchar(64) not null,
        role varchar(16) not null,
        createby int not null,
        updatedby int not null,
        PRIMARY key(user_roll_id),
        CONSTRAINT user_role_fk_id FOREIGN KEY(user_id) REFERENCES users(user_id),
        CONSTRAINT user_role_created FOREIGN KEY(createby) REFERENCES users(user_id),
        CONSTRAINT user_role_updated FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );

    -- login logs
    CREATE TABLE login_log
    (
        user VARCHAR(128) NOT NULL,
        user_action VARCHAR(16) NOT NULL,
        action_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT login_log_created FOREIGN KEY(user) REFERENCES users(username)
    );

    -- Persons/Customer Table
    CREATE TABLE person(
        person_id int AUTO_INCREMENT ,
        account_group_id int not null,
        company_id int not null,
        fname varchar(32) not null,
        lname varchar(32) not null,
        alies_name varchar(128) not null,
        gender varchar(8) not null,
        email varchar(64) null,
        NID varchar(128) null,
        TIN varchar(32) null,
        office_address varchar(128) null,
        office_details varchar(256) null,
        official_phone varchar(16) null,
        personal_phone varchar(16) null,
        personal_phone_second varchar(16) null,
        fax varchar(16) null,
        website varchar(64) null,
        note varchar(256) null,
        person_type varchar(64) not null,
        -- person type (Permenant customer, Safar, staff, share holders)
        added_date bigint not null,
        createby int not null,
        updatedby int not null,
        PRIMARY KEY(person_id),
        FOREIGN KEY(createby) REFERENCES users(user_id),
        FOREIGN KEY(updatedby) REFERENCES users(user_id),
        FOREIGN key(company_id) REFERENCES company(company_id),
        FOREIGN key(account_group_id) REFERENCES account_group(account_group_id)
    );

    --PERSONS ADDRESS
    CREATE TABLE person_address(
        person_address_id int AUTO_INCREMENT,
        person_id int,
        address_type varchar(32) not null,
        detail_address varchar(256) null,
        province varchar(16) null,
        district varchar(16) null,
        PRIMARY KEY(person_address_id),
        FOREIGN KEY (person_id) REFERENCES Person(person_id)
    );

    -- PERSON BANK ACCOUNT DETAILS
    CREATE TABLE person_bank_details(
        person_bank_details_id int AUTO_INCREMENT ,
        person_id int,
        bank_name varchar(64) null,
        account_type varchar(64) null,
        account_number varchar(32) null,
        currency varchar(8) null,
        details varchar(256) null,
        account_status varchar(32) null,
        PRIMARY KEY(person_bank_details_id),
        FOREIGN KEY (person_id) REFERENCES Person(person_id)
    );

    -- PERSON DOCUMENTS ATTACHMENT
    CREATE TABLE person_attachment(
        person_attachment_id int AUTO_INCREMENT ,
        person_id int,
        attachment_type varchar(128) null,
        attachment_name varchar(128) null,
        details varchar(128) null,
        createby int not null,
        updatedby int not null,
        PRIMARY KEY(person_attachment_id),
        FOREIGN KEY (person_id) REFERENCES Person(person_id),
        FOREIGN KEY(createby) REFERENCES users(user_id),
        FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );
 
    -- Daily Customer table
    CREATE TABLE daily_customer(
        daily_customer_id int AUTO_INCREMENT ,
        fname varchar(32),
        lname varchar(32),
        father_name varchar(32),
        phone varchar(16),
        NID varchar(32),
        note varchar(256),
        createby int not null,
        updatedby int not null,
        PRIMARY key(daily_customer_id),
        FOREIGN KEY(createby) REFERENCES person(person_id),
        FOREIGN KEY(updatedby) REFERENCES person(person_id),
        FOREIGN KEY(createby) REFERENCES users(user_id),
        FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );

 
-- Banking
-- 3: DEBT/REVENUE
-- 4: CREDIET/EXPENSES
-- 5: ACCOUNTING

    -- Daily customer transactions
    CREATE TABLE daily_customer_transaction(
        id int AUTO_INCREMENT ,
        person_sender int not null,
        -- company person
        person_receiver int not null,
        -- company person
        money_sender_id int not null,
        money_receiver_id int,
        money_receiver_name varchar(64) null,
        amount int not null,
        commision int not null,
        status varchar(16) not null,
        createby int not null,
        updatedby int not null,
        PRIMARY key(id),
        FOREIGN key(money_sender_id) REFERENCES daily_customer(daily_customer_id),
        FOREIGN key(money_receiver_id) REFERENCES daily_customer(daily_customer_id),
        FOREIGN KEY(createby) REFERENCES person(person_id),
        FOREIGN KEY(updatedby) REFERENCES person(person_id),
        FOREIGN KEY(person_sender) REFERENCES person(person_id),
        FOREIGN KEY(person_receiver) REFERENCES person(person_id)
    );

    
    -- Accounts group
    CREATE TABLE account_group(
        account_group_id int AUTO_INCREMENT ,
        group_name varchar(128) not null,
        account_type varchar(32) not null,
        -- account types
            -- assets
            -- liablity
            -- revenue
            -- expenses
            -- capital
        statement_type varchar(128) not null,
        -- Statement Type 
            -- Balance sheet
            -- Income Sheet
        parent_id INT,
        PRIMARY key(account_group_id)
    );

    -- PHP code to retreive the catagories with nth subcatagories
    --  try {
    --          $pdo = getPdo();

    --          $sql  = 'select * from products_categories where parent_id = 0';           
    --          $stmt = $pdo->prepare($sql);
    --          $stmt->execute(); 

    --          $data = [];

    --          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    --              echo $row['category_name'] . '<br>';
    --              getSubCategories($row['category_id'], 0);
    --          }                  

    --     } catch (Exception $e) {
    --         echo $e->getMessage();
    --     }

    -- function getSubCategories($parent_id, $level) {

    --     try {

    --         $pdo = getPdo();

    --         $sql = "select * from products_categories where parent_id = '$parent_id'";                

    --         $stmt = $pdo->prepare($sql);
    --         $stmt->execute(); 

    --         $data = [];  

    --         $level++;                 

    --         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    --             echo str_repeat("-", ($level * 4)) . $row['category_name'] . '<br>';                    
    --             getSubCategories($row['category_id'], $level);                                    
    --         } 

    --     } catch (Exception $e) {
    --         echo $e->getMessage();
    --     }                 
    -- }

    -- chart of account
    CREATE TABLE chartofaccount(
        id int AUTO_INCREMENT ,
        person_id int not null,
        account_name varchar(128) not null,
        account_group_id int not null,
        account_type varchar(32) not null,
        -- account type
            -- payable
            -- receivable
        date datetime,
        createby int not null,
        updatedby int not null,
        parent_id int,
        PRIMARY key(id),
        FOREIGN key(account_group_id) REFERENCES account_group(account_group_id),
        FOREIGN key(person_id) REFERENCES person(person_id),
        FOREIGN KEY(createby) REFERENCES users(user_id),
        FOREIGN KEY(updatedby) REFERENCES users(user_id)
    );

    -- chart of account permissions
    CREATE TABLE account_permission(
        id int AUTO_INCREMENT ,
        user_id int not null,
        account_id int not null,
        PRIMARY key(id),
        FOREIGN key(user_id) REFERENCES users(user_id),
        FOREIGN key(account_id) REFERENCES chartofaccount(id)
    );

    -- vouchers table
    CREATE TABLE voucher(
        id int AUTO_INCREMENT ,
        person_id int not null,
        voucher_number varchar(64) null,
        type varchar(32) not null,
        -- types
            -- payment
            -- recipt
            -- journal
            -- contra
        voucher_date datetime not null,
        note varchar(256) null,
        PRIMARY key(id),
        FOREIGN key(person_id) REFERENCES person(person_id)
    );

    -- Voucher permission table like ahmad can see mahmmod vouchers
    CREATE TABLE voucher_permission(
        id int AUTO_INCREMENT ,
        user_id int not null,
        -- this is the user which using the system now (online user)
        user_id_voucher int not null,
        -- this another user which have many vouchers and the first user will be able to see this users transaction if allowed
        PRIMARY key(id),
        FOREIGN key(user_id) REFERENCES users(user_id),
        FOREIGN key(user_id) REFERENCES users(user_id)
    );

    -- General Leadger table
    CREATE TABLE general_leadger(
        id int AUTO_INCREMENT ,
        person_id int not null,
        voucher_number int not null,
        account_number int not null,
        type varchar(16),
        -- Type
            -- debt
            -- credit
        amount int,
        remarks varchar(256),
        date datetime,
        cleared varchar(16) null,
        cleared_date datetime null,
        PRIMARY key(id),
        FOREIGN key(voucher_number) REFERENCES voucher(id),
        FOREIGN key(account_number) REFERENCES chartofaccount(id),
        FOREIGN key(person_id) REFERENCES person(person_id)
    );

    -- ledger permission table like ahmad can see mahmmod vouchers
    CREATE TABLE ledger_permission(
        id int AUTO_INCREMENT ,
        user_id int not null,
        -- this is the user which using the system now (online user)
        user_id_ledger int not null,
        -- this another user which have many vouchers and the first user will be able to see this users transaction if allowed
        PRIMARY key(id),
        FOREIGN key(user_id) REFERENCES users(user_id),
        FOREIGN key(user_id) REFERENCES users(user_id)
    );

-- 6: REPORTS
-- 7: SETTINGS/OPTIONS
-- 8: logs table
    -- Daily log data table
    CREATE TABLE logs_data
    (
        user INT NOT NULL,
        tble VARCHAR(128) NOT NULL,
        user_action VARCHAR(16) NOT NULL,
        details VARCHAR(1200) NOT NULL,
        action_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT FK_USER_ID FOREIGN KEY(user) REFERENCES users(user_id)
    );

-- Approval table for all operations like adding person, transactions ...
    CREATE TABLE approval(
        approval_id int AUTO_INCREMENT ,
        table_name varchar(64) not null,
        user_id int not null,
        approved_user int not null,
        status varchar(16) not null,
        PRIMARY key(approval_id),
        FOREIGN key(approved_user) REFERENCES users(user_id),
        FOREIGN key(user_id) REFERENCES person(person_id),
        FOREIGN key(user_id) REFERENCES voucher(person_id),
        FOREIGN key(user_id) REFERENCES chartofaccount(person_id),
        FOREIGN key(user_id) REFERENCES general_leadger(person_id)
    );

    -- System Moduls
    CREATE TABLE module(
        module_id int AUTO_INCREMENT ,
        name varchar(256) not null,
        languag varchar(64) not null,
        parent_id int null,
        PRIMARY key(module_id)
    );
