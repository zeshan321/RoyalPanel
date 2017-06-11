package com.zeshanaslam.willowcore.connection;

import com.zeshanaslam.willowcore.Main;

import javax.net.ssl.KeyManagerFactory;
import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManagerFactory;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.security.*;
import java.security.cert.CertificateException;

public class TLSHandler {
    public SSLContext createTLSContext() throws NoSuchAlgorithmException, CertificateException, IOException, KeyStoreException, KeyManagementException, UnrecoverableKeyException {
        char[] ksPassword = Main.plugin.config.key.toCharArray();
        char[] ctPassword = Main.plugin.config.store.toCharArray();

        KeyStore keyStore = KeyStore.getInstance("JKS");
        keyStore.load(new FileInputStream(new File(Main.plugin.getDataFolder() + File.separator + "keystore.jks")), ksPassword);

        KeyManagerFactory keyManager = KeyManagerFactory.getInstance("SunX509");
        keyManager.init(keyStore, ctPassword);

        TrustManagerFactory trustManager = TrustManagerFactory.getInstance("SunX509");
        trustManager.init(keyStore);

        SSLContext sslContext = SSLContext.getInstance("TLS");
        sslContext.init(keyManager.getKeyManagers(), trustManager.getTrustManagers(), null);

        return sslContext;
    }
}
