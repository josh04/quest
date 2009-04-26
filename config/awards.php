<?

//THIS GETS ALL THE AWARDS FOR THE PLAYER WITH ID $ID
function get_awards(&$profile, &$db) {

//First, we connect to the database and pull all da datas.

$awards_array = array();

array_push($awards_array,$awardid);

if($profile->rank=="admin") array_push($awards_array,"Chairman - This agent is an administrator!");
if($profile->kills>1000) array_push($awards_array,"Assassin - This agent has pwned lots of n00bs");
if($profile->deaths>50) array_push($awards_array,"Quitter - This agent gives up easily.");
if($profile->username=="Grego") array_push($awards_array,"Power of the Grego - This agent is Greg!");

return $awards_array;
}

?>