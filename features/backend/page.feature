#@mink:zombie
@backend
Feature: Page Administration
    In order to manage pages
    I need to be able to list, read and update pages

    Scenario: An admin see a tree of pages
        Given I am connected with "admin" and "admin" on "/admin"
        When I follow dashboard "Pages" link "List"
        Then I should see the "sandbox" website selection and a link with selected locale "en"
        #And I should see a tree of pages

#Scenario: An admin see block configurations for a page
#    Given I am on "/admin/cms/page/website/sandbox/en"
#    And I follow "Main navigation <not editable>"
#    When I follow "Homepage"
#    Then I should see a list of blocks

#Scenario: An admin edit a block
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    And I press "/website/sandbox/page/home/content/main" block edit button
#    And I fill in the following:
#        | Title     | behat test            |
#        | Content   | behat content block   |
#    When I follow "Save"
#    Then I should see the block highlighted
#
#Scenario: An admin edit SEO parameters
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    And I follow "SEO"
#    And I fill in the following:
#        | Title         | Behat edit Homepage        |
#        | Keywords      | cms, behat                 |
#        | Description   | homepage edited with behat |
#    When I follow "Save"
#    Then I should see "Item has been successfully updated."
#
#Scenario: An admin create a subpage
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    And I follow "create a new subpage"
#    And I fill in the following:
#        | id            | sub-home  |
#        | menu_entry    |  sub-home |
#        | template      | default   |
#    When I follow "Save"
#    Then I should see "Item has been successfully updated."
#
#Scenario: An admin preview modification on front
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    When I follow "preview your modification on front"
#    Then I should see the sub-home page
#
#Scenario: An admin apply modification on front
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    When I follow "Clear cache to see your modification on front"
#    Then I should see "The cache for this page has been cleared"
#
#Scenario: An admin delete a page
#    Given I am on "/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
#    And I follow "delete this page and all its children"
#    Then I should see "Confirm deletion"
#    When I follow "Yes, delete"
#    Then I should see "Item has been deleted successfully."
