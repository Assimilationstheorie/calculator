<?php
//SKKRR
class Taschenrechner {
  protected $zahl;
  protected $final_number;
  protected $zahlen = [
      "eins" => 1,
      "ein" => 1,
      "zwei" => 2,
      "drei" => 3,
      "vier" => 4,
      "fünf" => 5,
      "sechs" => 6,
      "sieben" => 7,
      "acht" => 8,
      "neun" => 9,
      "zehn" => 10,
      "elf" => 11,
      "zwölf" => 12,
      "dreizehn" => 13,
      "vierzehn" => 14,
      "fünfzehn" => 15,
      "sechszehn" => 16,
      "siebzehn" => 17,
      "achtzehn" => 18,
      "neunzehn" => 19,
      "zwanzig" => 20,
      "dreißig" => 30,
      "vierzig" => 40,
      "fünfzig" => 50,
      "sechszig" => 60,
      "siebzig" => 70,
      "achtzig" => 80,
      "neunzig" => 90,
      "hundert" => 100,
      "einhundert" => 100,
      "tausend" => 1000,
      "eintausend" => 1000,
      "hunderttausend" => 100000
   ];

  public function translate($zahl) {
    $this->zahl = $zahl;
    //Ist die übergebene Zahl ein Int?
    if(is_numeric($this->zahl)) {
      //Ja
      $this->final_number = $this->zahl;
    } else {
      //Befindet sich die Zahl schon in dem ZahlenArray als Integer?
      if(in_array($this->zahl, $this->zahlen)) {
        //Setzt die $final_number den Wert der übergebeben Zahl
        $this->final_number = $this->zahl;
        //Befindet sich die Zahl in dem ZahlenArray als String?
      } elseif(isset($this->zahlen[$this->zahl])) {
        //Setzt die $final_number den Wert der übergebeben Zahl
        $this->final_number = $this->zahlen[$this->zahl];
      } else {
        //Alle Sonderfälle
        $this->final_number = $this->butcher();
      }
    }
    return $this->final_number;
}

  private function butcher() {
    //Tausend kommt nur einmal vor
    //Hundert 2x vorkommen kann
    //Und kann 2x vorkommen
    $key_words = ['tausend', 'hundert', 'und'];
    //Prüft, ob es das Wort "Tausend" gibt
    if(preg_match("/$key_words[0]/", $this->zahl)) {
      //String wird in zwei Teile geteilt - Das Wort "Tausend" ist hierbei der Trenner
      $tausend = explode($key_words[0], $this->zahl);
      //Prüfen, ob hinter "tausend" noch etwas ist
      if(empty($tausend[1])) {
        //Hinter Tausend befindet sich nichts mehr!!!!!!!!
        //Sucht nach dem Zahlenwort in dem Array
        if(isset($this->zahlen[$tausend[0]])) {
          //Wenn da, nimmt es x tausend und setzt es als final_number
           $this->final_number = $this->zahlen[$tausend[0]] * $this->zahlen['tausend'];
        } else {
          //Hier kommt, wenn die Zahl nicht im Array ist - Es geht um das Wort VOR dem Wort Tausend! - Nur noch "und" oder "hundert"
          echo $this->detective($tausend[0]);
        }
      } else {
          echo ($this->detective($tausend[0]) * $this->zahlen['tausend']) + $this->detective($tausend[1]) ;
      }
  } else {
    $key_words = ['hundert', 'und'];
    //Schaut ob das Wort "hundert" drin ist
    if(preg_match("/$key_words[0]/", $this->zahl)) {
      $hundert = explode($key_words[0], $this->zahl);
      if(empty($hundert[1])) {
        $this->final_number = $this->zahlen[$hundert[0]] * $this->zahlen['hundert'];
      } else {
        if(preg_match("/$key_words[1]/", $hundert[1])) {
          $und = explode($key_words[1], $hundert[1]);
          $this->final_number = ($this->zahlen[$hundert[0]] * $this->zahlen['hundert']) + $this->zahlen[$und[0]] + $this->zahlen[$und[1]];
        } else {
          $this->final_number = ($this->zahlen[$hundert[0]] * $this->zahlen['hundert']) + $this->zahlen[$hundert[1]];
        }
      }
    } else {
      //ist es nicht
    }
  }
  return $this->final_number;
}


  //Irgendwelche Zahlen interpretieren
  private function detective($zahl) {
    $key_words = ['hundert', 'und'];
    //Schaut ob das Wort "hundert" drin ist IM ERSTEN TEIL vor TAUSEND
    if(preg_match("/$key_words[0]/", $zahl)) {
      //Schneidet alles nach "hundert" - Nimmt den ersten Teil
      $hundert = explode($key_words[0], $zahl);
      //Nimm die Zahl vor dem Wort "hundert" und nimmt sie mal 100
      $vorHundert = $this->zahlen[$hundert[0]] * $this->zahlen['hundert'];
      //Hinter hundert und vor tausend - die zahl - Schaut, ob das Wort ein "und" hat
      if(preg_match("/$key_words[1]/", $hundert[1])) {
        //Das Und wird abgetrennt
        $und = explode($key_words[1], $hundert[1]);
        //Setzen die erste Zahl nach "hundert" und addiere die beiden Zahlen zwischen "und" - vor tausend
        $final = $vorHundert + $this->zahlen[$und[1]] + $this->zahlen[$und[0]];
      } else {
        //Keine UND Zahl vor tausend - Geh in das Array
        if(!empty($this->zahlen[$hundert[0]]) && !empty($this->zahlen[$hundert[1]])) {
          //BUG
          $final = ($this->zahlen[$hundert[0]] * 100) + $this->zahlen[$hundert[1]];

        } else {
          //BUG
          $final = $this->zahlen[$hundert[0]];
        }
      }
    } else {
      if(preg_match("/$key_words[1]/", $zahl)) {
        //Das Und wird abgetrennt
        $und = explode($key_words[1], $zahl);
        //Setzen die erste Zahl nach "hundert" und addiere die beiden Zahlen zwischen "und" - vor tausend
        $final = $this->zahlen[$und[1]] + $this->zahlen[$und[0]];
      } else {
        //Keine UND findet
          $final = $this->zahlen[$zahl];
      }
    }
      return $final;
  }
}

$service = new Taschenrechner();
//525386
//Dreihunder geht nicht
$zahl = "dreitausendsechshundert";
 // $zahl = 2000;

echo "<hr>";
echo "<pre>";
var_dump($service->translate($zahl));
echo "</pre>";
