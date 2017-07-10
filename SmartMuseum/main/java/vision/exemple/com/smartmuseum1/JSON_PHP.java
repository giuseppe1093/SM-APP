package vision.exemple.com.smartmuseum1;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.media.MediaPlayer;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;


public class JSON_PHP extends AppCompatActivity{

    Store store=Store.getInstance();

    @Override
    protected void onCreate( Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.v("JSON_PHP","onCreate");
        Intent intent = getIntent() ;
        String CodiceReperto = intent.getStringExtra("codice reperto");
        new PHP().execute(CodiceReperto);
    }
    private class PHP extends AsyncTask<String,Void,String> {
        String[] riga = null;
        Bitmap immagineBit=null;
        MediaPlayer musica=null;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            immagineBit=null;
            store.setRiga(riga);
            store.setImmagine(immagineBit);
            store.setMusica(musica);
        }

        @Override
        protected String doInBackground(String... params) {
            String CodiceReperto=params[0];
            String risultato = null;
            String url="";
            try {
                url ="http://192.168.43.175/SmartMuseum";
                URL URLsito = new URL(url+"/queryphp.php?CodiceReperto="+CodiceReperto);                                                         //indirizzo del sito
                HttpURLConnection client = (HttpURLConnection) URLsito.openConnection();            //connessione al server dove sta il sito
                client.setRequestMethod("GET");
                client.connect();
                InputStream oggetto = new BufferedInputStream(client.getInputStream());
                try {                                                                           //coversione risposta in stringa
                    BufferedReader reader = new BufferedReader(new InputStreamReader(oggetto));
                    StringBuilder sb = new StringBuilder();
                    String line;
                    while ((line = reader.readLine()) != null) {
                        sb.append(line).append("\n");
                    }
                    oggetto.close();
                    risultato = sb.toString();
                } catch (Exception e) {
                    Toast.makeText(JSON_PHP.this,"Errore 1: reperto non trovato",Toast.LENGTH_LONG).show();
                    //Log.e("TEST", "Errore nel convertire il risultato" + e.toString());
                }
                if(risultato=="query non trovata"){
                    Toast.makeText(JSON_PHP.this,"Errore 2: Reperto non trovato",Toast.LENGTH_LONG).show();
                    finish();
                }
            } catch (Exception e) {
                Toast.makeText(JSON_PHP.this,"errore di connessione",Toast.LENGTH_LONG).show();
            }
            try {                                                               // parsing dei dati arrivati in formato json
                JSONArray jArray =new JSONArray(risultato);
                JSONObject json_data = null;
                riga=null;
                try {
                    for (int i = 0; i < jArray.length(); i++) {
                        json_data = jArray.getJSONObject(i);
                    }
                    Log.i("TEST", json_data.getInt("CodiceReperto") + json_data.getString("Nome") + json_data.getString("Artista") +
                            json_data.getString("Descrizione") + json_data.getString("Qrcode") + json_data.getString("Immagine") + json_data.getString("Audio") +
                            json_data.getInt("Museo_idMuseo"));   //controllo sui dati
                    riga = new String[]{String.valueOf(json_data.getInt("CodiceReperto")), json_data.getString("Nome"), json_data.getString("Artista"),
                            json_data.getString("Descrizione"), (json_data.getString("Qrcode")), (url + "/immagini/opere/immagine/" + json_data.getString("Immagine")),
                            (url + "/immagini/opere/audio/" + json_data.getString("Audio")), String.valueOf(json_data.getInt("Museo_idMuseo"))};                        // creazione tabella in app (conversion o lettura dei dati e riordinamento
                }catch (Exception e){
                    Toast.makeText(JSON_PHP.this,"Errore nel parsing",Toast.LENGTH_LONG).show();
                }
                try {
                    InputStream in = new java.net.URL(riga[5].replace(" ","%20")).openStream();
                    immagineBit = BitmapFactory.decodeStream(in);
                    musica = MediaPlayer.create(JSON_PHP.this,Uri.parse(riga[6].replace(" ","%20")));
                } catch (Exception e) {
                    Toast.makeText(JSON_PHP.this,"Errore nel caricamento dei file multimediali",Toast.LENGTH_LONG).show();
                }

            } catch (JSONException e) {
                Log.e("log_tag", "Errore parsing data" + e.toString());
            }
            store.setImmagine(immagineBit);
            store.setRiga(riga);
            store.setMusica(musica);
            return null;
        }


        @Override
        protected void onPostExecute(String s) {
            super.onPostExecute(s);
            try {
                Intent intent =new Intent(JSON_PHP.this,SCHEDA.class);
                startActivity(intent);
                JSON_PHP.this.finish();
            }catch (Exception e){
                Toast.makeText(JSON_PHP.this,"impossibile chiudere",Toast.LENGTH_LONG).show();
            }
        }
    }
}