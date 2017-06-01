package com.zeshanaslam.willowcore;

import com.sun.management.OperatingSystemMXBean;
import com.zeshanaslam.willowcore.connection.SQL;
import com.zeshanaslam.willowcore.connection.Socket;
import com.zeshanaslam.willowcore.utils.System;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.core.Logger;
import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.scheduler.BukkitRunnable;

import javax.management.MBeanServerConnection;
import java.io.IOException;
import java.lang.management.ManagementFactory;

public class Main extends JavaPlugin {

    public static Socket socket;
    public static SQL sql;
    public static ConfigLoader config;

    public int tps;

    @Override
    public void onEnable() {
        saveDefaultConfig();

        // Load config
        config = new ConfigLoader(this);

        // Setup web socket
        new Thread() {
            public void run() {
                socket = new Socket();
                socket.start();
            }
        }.start();

        // Setup SQL
        new Thread() {
            public void run() {
                sql = new SQL();
            }
        }.start();

        // Start console capture
        Logger log = (Logger) LogManager.getRootLogger();
        log.addAppender(new CaptureConsole(this));

        // Register listeners
        getServer().getPluginManager().registerEvents(new Events(this), this);

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
        // Stop socket
        try {
            socket.stop();
            socket = null;
        } catch (IOException | InterruptedException e) {
            e.printStackTrace();
        }
    }
}
