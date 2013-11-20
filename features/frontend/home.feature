@frontend
Feature: PrestaCMS Sandbox
    In order to start hacking the PrestaCMS sandbox
    As a smart developer
    I need to be able to see the Home page

Scenario: View the home page
    Given I am on the homepage
    Then I should see "Welcome on PrestaCMS demonstration"
