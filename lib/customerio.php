<?php
use Guzzle\Http\Client;

class CustomerIO extends Client {
  private $base_url = 'https://track.customer.io/api/v1/customers';

  private function authorize_request($req) {
    require __DIR__ . '/../config/services.php';
    $req->setAuth($customer_io['site_id'], $customer_io['api_key']);
    return $req;
  }
  public function identify_user($user) {
    $req = $this->put($this->base_url.'/'.$user->id, array(),
      array(
        'email' => $user->email,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name
      )
    );
    $req = $this->authorize_request($req);
    $res = $req->send();
    return $res->isSuccessful();
  }
  public function send_event($user, $event_data) {
    $req = $this->post($this->base_url.'/'.$user->id.'/events', array(), $event_data);
    $req = $this->authorize_request($req);
    $res = $req->send();
    return $res->isSuccessful();
  }
  public function identify_and_send($user, $event_data) {
    if ($this->identify_user($user)) {
      return $this->send_event($user, $event_data);
    } else {
      return false;
    }
  }
}