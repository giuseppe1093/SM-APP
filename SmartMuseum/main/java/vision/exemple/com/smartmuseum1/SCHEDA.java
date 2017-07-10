package vision.exemple.com.smartmuseum1;

import android.content.Intent;
import android.graphics.Bitmap;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.speech.tts.TextToSpeech;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import java.util.Locale;

public class SCHEDA extends AppCompatActivity {
    Store store=Store.getInstance();
    Boolean audio = true;
    TextToSpeech sintetizzatore ;
    Boolean cont=false;
    String[]riga=store.getRiga();
    Bitmap immagine=store.getImmagine();
    final MediaPlayer musica=store.getMusica();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.v("SCHEDA","onCreate");
        setContentView(R.layout.activity_scheda);

        String NomOpera = riga[1];
        String NomArtista=riga[2];
        final String Descrizione=riga[3];
        TextView Opera =(TextView)findViewById(R.id.Opera);
        TextView Artista = (TextView)findViewById(R.id.Artista);
        TextView DescrizioneView=(TextView)findViewById(R.id.Descrizione);
        ImageView immaginView=(ImageView)findViewById(R.id.imageView);
        final Button contrMusic=(Button)findViewById(R.id.musica);
        Button QR_scann=(Button)findViewById(R.id.QR_scann);
        final Button SintVocal = (Button)findViewById(R.id.sintetizzatore);

        musica.start();
        immaginView.setImageBitmap(immagine);
        Opera.setText(NomOpera);
        Artista.setText(NomArtista);
        DescrizioneView.setText(Descrizione);
        sintetizzatore=new TextToSpeech(getApplicationContext(), new TextToSpeech.OnInitListener() {
            @Override
            public void onInit(int status) {
                if(status != TextToSpeech.ERROR) {
                    sintetizzatore.setLanguage(Locale.ITALY);
                }
            }
        });

        contrMusic.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (audio == true){
                    musica.pause();
                    contrMusic.setText("Play Musica");
                    audio =false;
                }else {
                    musica.start();
                    contrMusic.setText("Pause Musica");
                    audio=true;
                    if (cont==true){
                        sintetizzatore.stop();
                    }
                }
            }
        });
        QR_scann.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(SCHEDA.this,QR_CODE.class);
                startActivity(intent);
                SCHEDA.this.finish();
            }
        });

        SintVocal.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                if (sintetizzatore.isSpeaking()==false){
                    musica.pause();
                    contrMusic.setText("Play Musica");
                    audio =false;
                    cont=true;
                    SintVocal.setText("Stop Testo");
                    sintetizzatore.speak(Descrizione,TextToSpeech.QUEUE_ADD,null);
                }else {
                    SintVocal.setText("Audio Testo");
                    sintetizzatore.stop();
                    cont=false;
                }

            }
        });

    }

    @Override
    protected void onPause() {
        super.onPause();
        musica.pause();
        sintetizzatore.stop();
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        if(audio=true){
            musica.start();
        }
    }
}
