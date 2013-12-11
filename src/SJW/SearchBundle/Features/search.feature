Feature: Population search App
    In order to find out about town population
    As a visitor
    I want to be able to search towns with one input field

    Scenario: Viewing the search at website root
      When I am on homepage
      And I should see a "#search-input" element

    Scenario: Searching for town
      Given I am on homepage
      When I fill in "search" with "Tampere"
      And I press "submit"
      And I wait for 2 seconds
      Then I should see "Tampere Keskus"