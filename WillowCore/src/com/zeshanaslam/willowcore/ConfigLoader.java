package com.zeshanaslam.willowcore;

public class ConfigLoader {

    // Socket
    public int port;
    public String key;
    public String store;

    // SQL
    public String ip;
    public String user;
    public String pass;
    public String db;
    private Main plugin;

    public ConfigLoader(Main plugin) {
        this.plugin = plugin;

        // Socket
        port = plugin.getConfig().getInt("port");
        key = plugin.getConfig().getString("key-pass");
        store = plugin.getConfig().getString("store-pass");

        // SQL
        ip = plugin.getConfig().getString("server");
        user = plugin.getConfig().getString("user");
        pass = plugin.getConfig().getString("password");
        db = plugin.getConfig().getString("database");
    }
}
