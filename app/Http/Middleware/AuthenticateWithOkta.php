<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class AuthenticateWithOkta
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isAuthorized($request)) {
            return $next($request);
        } else {
            return response('Unauthorized.', 401);
        }
    }

    public function isAuthorized($request)
    {
        if (! $request->header('Authorization')) {
            return false;
        }

        $authType = null;
        $authData = null;

        // Extract the auth type and the data from the Authorization header.
        @list($authType, $authData) = explode(" ", $request->header('Authorization'), 2);

        // If the Authorization Header is not a bearer type, return a 401.
        if ($authType != 'Bearer') {
            return false;
        }

        // Attempt authorization with the provided token
        try {

            // Setup the JWT Verifier
            // $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
            //                 ->setAdaptor(new \Okta\JwtVerifier\Adaptors\SpomkyLabsJose())
            //                 ->setAudience('api://default')
            //                 ->setClientId('0oaczby8te3v2Tm2I4x6')
            //                 ->setIssuer('http://localhost:8000/')
            //                 ->build();

            // Verify the JWT from the Authorization Header.
            // $jwt = $jwtVerifier->verify($authData);
            // $headers = ['Authorization' => 'Basic ${Base64(0oaczby8te3v2Tm2I4x6:none)}'];
            

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://dev-493821.okta.com/v1',
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ]);
            $body = [''];
            $request = new Request('POST', '/introspect?client_id=0oaczby8te3v2Tm2I4x6', [
                'form_params' => [
                    'token' => $authData,
                    'token_type_hint' => 'acess_token',
                    ]]);
            $promise = $client->sendAsync($request);
            $promise->then(
                function (ResponseInterface $res) {
                    if ($res->getStatusCode()!=200) return false;
                },
                function (RequestException $e) {
                    echo $e->getMessage() . "\n";
                    echo $e->getRequest()->getMethod();
                    return false;
                }
            );
            
        } catch (\Exception $e) {

            // We encountered an error, return a 401.
            return false;
        }

        return true;
    }

}
