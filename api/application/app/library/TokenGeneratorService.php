<?php
use Phalcon\Security\Exception;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class TokenGeneratorService
{
    private const AUDIENCE = 'https://erp.io/';
    private const ISSUER = 'https://phalcon.io/';
    private const ID = 'abcd123456789';
    private const PATHPHRASE = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

    public static function getToken($id) {

        // Defaults to 'sha512'
        $signer  = new Hmac();

        // Builder object
        $builder = new Builder($signer);

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();

        // Setup
        $builder
            ->setAudience(self::AUDIENCE)  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp
            ->setId(self::ID)                    // JTI id
            ->setIssuedAt($issued)                      // iat
            ->setIssuer(self::ISSUER)           // iss
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject($id)   // sub
            ->setPassphrase(self::PATHPHRASE)                // password
        ;

        // Phalcon\Security\JWT\Token\Token object
        $tokenObject = $builder->getToken();

        // The token
        return $tokenObject->getToken();

    }

    /**
     * @param $token
     * @return bool
     * @throws Phalcon\Security\JWT\Exceptions\ValidatorException
     */
    public static function validateToken($token) {
        try {
            $now = new DateTimeImmutable();
            $issued = $now->getTimestamp();
            $notBefore = $now->modify('-1 minute')->getTimestamp();
            $expires = $now->getTimestamp();
            // Throw exceptions if those do not validate

            // Defaults to 'sha512'
            $signer = new Hmac();

            // Parse the token
            $parser = new Parser();
            // Phalcon\Security\JWT\Token\Token object

            $tokenObject = $parser->parse($token);
            // Phalcon\Security\JWT\Validator object
            $validator = new Validator($tokenObject, 100); // allow for a time shift of 100

            $validator
                ->validateAudience(self::AUDIENCE)
                ->validateExpiration($expires)
                ->validateId(self::ID)
                ->validateIssuedAt($issued)
                ->validateIssuer(self::ISSUER)
                ->validateNotBefore($notBefore)
                ->validateSignature($signer, self::PATHPHRASE);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $token
     * @return \Phalcon\Security\JWT\Token\Token
     */
    public static function parseToken($token) {
        $parser = new Parser();

        return $parser->parse($token);
    }
}