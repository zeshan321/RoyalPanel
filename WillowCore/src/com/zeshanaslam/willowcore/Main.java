package com.zeshanaslam.willowcore;

import com.zeshanaslam.willowcore.commands.StatsCommand;
import com.zeshanaslam.willowcore.connection.SQL;
import com.zeshanaslam.willowcore.connection.Socket;
import com.zeshanaslam.willowcore.statistics.PlayerStatsListener;
import com.zeshanaslam.willowcore.statistics.PlayerStatsManager;
import com.zeshanaslam.willowcore.utils.System;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.core.Logger;
import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.scheduler.BukkitRunnable;

import java.io.File;
import java.io.IOException;

public class Main extends JavaPlugin {

    public static Main plugin;
    public Socket socket;
    public SQL sql;
    public ConfigLoader config;
    public PlayerStatsManager playerStatsManager;

    public int tps;

    @Override
    public void onEnable() {
        saveDefaultConfig();

        plugin = this;

        // Load config
        config = new ConfigLoader(this);

        // Load default keystore
        if (!new File(getDataFolder() + File.separator + "keystore.jks").exists()) {
            saveResource("keystore.jks", false);
        }

        // Setup web socket
        new Thread(() -> {
            socket = new Socket();
            socket.start();
        }).start();

        // Setup SQL
        new Thread(() -> {
            sql = new SQL();
            playerStatsManager = new PlayerStatsManager();
        }).start();

        // Start console capture
        Logger log = (Logger) LogManager.getRootLogger();
        log.addAppender(new CaptureConsole(this));

        // Register listeners
        getServer().getPluginManager().registerEvents(new Events(this), this);
        getServer().getPluginManager().registerEvents(new PlayerStatsListener(), this);

        // Register commands
        getCommand("stats").setExecutor(new StatsCommand(this));

        // Send socket server status updates
        new BukkitRunnable() {
            public void run() {
                Runtime runtime = Runtime.getRuntime();
                long ramTotal = runtime.totalMemory();
                long ramUsed = ramTotal - runtime.freeMemory();

                socket.sendMessage("RAM: " + ramUsed + " " + ramTotal);
                socket.sendMessage("TPS: " + (int) Math.round(System.getRecentTps()[0]));
                try {
                    socket.sendMessage("CPU: " + (int) System.getProcessCpuLoad());
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }.runTaskTimerAsynchronously(this, 0, 100);
    }

    @Override
    public void onDisable() {
        // Clear stats
        playerStatsManager.playTime.clear();
        playerStatsManager.stats.clear();

        // Stop socket
        try {
            socket.stop();
            socket = null;
        } catch (IOException | InterruptedException e) {
            e.printStackTrace();
        }
    }
}
