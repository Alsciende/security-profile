Feature:
  I can authenticate with a user and some roles

  Scenario: I can get details about my user
    Given the request header "Authorization" is "Bearer full"
    When I request GET "/users/me"
    Then the response code is "200"
    And I load the response as JSON
    And the JSON node "success" should be equal to "true"
    And the JSON node "data.username" should be equal to "test@example.com"
    And the JSON node "data.roles" should have 2 elements

  Scenario: I can get details about my token
    Given the request header "Authorization" is "Bearer partial"
    When I request GET "/tokens/me"
    Then the response code is "200"
    And I load the response as JSON
    And the JSON node "success" should be equal to "true"
    And the JSON node "data.user.username" should be equal to "test@example.com"
    And the JSON node "data.user.roles" should have 2 element
    And the JSON node "data.token.roles" should have 1 element

  Scenario: I can create a token
    Given the request header "Authorization" is "Bearer full"
    Given the request body is:
    """
    {"roles":["ROLE_VIEW"]}
    """
    When I request POST "/tokens"
    Then the response code is "200"
    And I load the response as JSON
    And the JSON node "success" should be equal to "true"
    And the JSON node "data.roles" should have 1 element

  Scenario: I cannot create a token with more permissions
    Given the request header "Authorization" is "Bearer partial"
    Given the request body is:
    """
    {"roles":["ROLE_VIEW","ROLE_EDIT"]}
    """
    When I request POST "/tokens"
    Then the response code is "200"
    And I load the response as JSON
    And the JSON node "success" should be equal to "false"
    And the JSON node "message" should be equal to "Cannot grant role [ROLE_EDIT] to token."

  Scenario: I can use a token to access a protected route if the token has the correct permissions
    Given the request header "Authorization" is "Bearer partial"
    When I request GET "/view"
    Then the response code is "200"

  Scenario: I cannot use a token to access a protected route if the token doesn't have the correct permissions
    Given the request header "Authorization" is "Bearer partial"
    When I request GET "/edit"
    Then the response code is "403"
