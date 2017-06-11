package com.zeshanaslam.willowcore.utils;

import com.sun.management.OperatingSystemMXBean;
import org.bukkit.Bukkit;
import org.bukkit.Server;

import java.lang.management.ManagementFactory;
import java.lang.reflect.Field;

public class System {

    private static Object minecraftServer;
    private static Field recentTps;

    public static double[] getRecentTps() {
        try {
            if (minecraftServer == null) {
                Server server = Bukkit.getServer();
                Field consoleField = server.getClass().getDeclaredField("console");
                consoleField.setAccessible(true);
                minecraftServer = consoleField.get(server);
            }
            if (recentTps == null) {
                recentTps = minecraftServer.getClass().getSuperclass().getDeclaredField("recentTps");
                recentTps.setAccessible(true);
            }
            return (double[]) recentTps.get(minecraftServer);
        } catch (IllegalAccessException | NoSuchFieldException ignored) {
        }
        return new double[]{20, 20, 20};
    }

    public static double getProcessCpuLoad() {
        OperatingSystemMXBean operatingSystemMXBean =
                (OperatingSystemMXBean) ManagementFactory.getOperatingSystemMXBean();

        return operatingSystemMXBean.getProcessCpuLoad() * 100;
    }
}
