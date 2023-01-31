# simple-php-jwt
An example of a class that generates, validates and allows you to get a payload by user id from a JWT token in php

## Exmple

```php
public function manipWithToken() {
        $token = $this->jwtService->generate(22); // token for user with id 22
        if($this->jwtService->validate($token))
          return $this->jwtService->getUserId($token); // returns 22
        return 0;
}
```
