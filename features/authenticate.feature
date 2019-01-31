Feature:
  I can authenticate with a user and some roles

  Scenario: I can use a token to authenticate
    Given the request "Authorization" header is "Bearer 123"
    When I request "/users/me"
    Then the response code is "200"
    And I load the response as JSON
    And the JSON node "data.username" should be equal to "test@example.com"
    And I print the response as JSON