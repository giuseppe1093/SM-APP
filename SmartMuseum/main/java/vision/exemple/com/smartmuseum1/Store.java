package vision.exemple.com.smartmuseum1;

import android.app.Application;
import android.graphics.Bitmap;
import android.media.MediaPlayer;
import android.util.Log;


public class Store extends Application{
    private String[] riga;
    private Bitmap immagine;
    private MediaPlayer musica;

    static Store instance;
    public static Store getInstance(){
        if (instance==null){
            Log.v("MyApplication","instance created");
            instance=new Store();
        }
        Log.v("MyApplication","instance returned");
        return instance;
    }

    @Override
    public void onCreate(){
        super.onCreate();
        Log.v("MyApplication","onCreate");
        Store store=getInstance();
        store.setRiga(getRiga());
        store.setImmagine(getImmagine());
        store.setMusica(getMusica());
    }
    public void setRiga(String[] rigain){
        this.riga=rigain;
    }
    public void setImmagine(Bitmap immaginein){
        this.immagine=immaginein;
    }
    public void setMusica(MediaPlayer musicain){
        this.musica=musicain;
    }
    public String[] getRiga(){
        return riga;
    }
    public Bitmap getImmagine(){
        return immagine;
    }
    public MediaPlayer getMusica(){
        return musica;
    }
}
