package com.zeshanaslam.willowcore;

public class ConfigLoader {

    private Main plugin;

    // Socket
    public int port;

    // SQL
    public String ip;
    public String user;
    public String pass;
    public String db;

    public ConfigLoader(Main plugin) {
        this.plugin = plugin;

        // Socket
        port = plugin.getConfig().getInt("port");

        // SQL
        ip = plugin.getConfig().getString("server");
        user = plugin.getConfig().getString("user");
        pass = plugin.getConfig().getString("password");
        db = plugin.getConfig().getString("database");
    }
}
