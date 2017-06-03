package com.zeshanaslam.willowcore.statistics;

import com.zeshanaslam.willowcore.Main;
import org.bukkit.entity.Player;
import org.bukkit.event.EventHandler;
import org.bukkit.event.EventPriority;
import org.bukkit.event.Listener;
import org.bukkit.event.player.AsyncPlayerChatEvent;
import org.bukkit.event.player.PlayerCommandPreprocessEvent;
import org.bukkit.event.player.PlayerJoinEvent;
import org.bukkit.event.player.PlayerQuitEvent;

import java.util.Calendar;

public class PlayerStatsListener implements Listener {

    @EventHandler(priority = EventPriority.MONITOR)
    public void onLeave(PlayerQuitEvent event) {
        Player player = event.getPlayer();

        // Save play time
        long difference = System.currentTimeMillis() - Main.plugin.playerStatsManager.playTime.get(player.getUniqueId().toString());
        long minutes = difference / (60 * 1000) % 60;

        Main.plugin.playerStatsManager.addStat(player, PlayerStatsManager.StatType.PLAY_TIME, (int) minutes);

        // Save all stats
        Main.plugin.playerStatsManager.savePlayerStats(player);
    }

    // Stats tracking
    @EventHandler(priority = EventPriority.MONITOR)
    public void onJoinStat(PlayerJoinEvent event) {
        Player player = event.getPlayer();

        Main.plugin.playerStatsManager.loadPlayerStats(player);
        Main.plugin.playerStatsManager.incrementStat(player, PlayerStatsManager.StatType.LOGIN);

        // Track play time
        Main.plugin.playerStatsManager.playTime.put(player.getUniqueId().toString(), System.currentTimeMillis());

        // Store last play
        Calendar calendar = Calendar.getInstance();
        calendar.setTime(new java.util.Date(System.currentTimeMillis()));
        String current = calendar.get(Calendar.YEAR) + "-" + (calendar.get(Calendar.MONTH) + 1) + "-" + calendar.get(Calendar.DAY_OF_MONTH);

        if (Main.plugin.sql.containsLastJoin(player)) {
            String date = Main.plugin.sql.getLastJoin(player);

            if (!date.equals(current)) {
                Main.plugin.sql.saveLastJoin(player);
                Main.plugin.sql.savePlayerCount(current);
            }
        } else {
            Main.plugin.sql.saveLastJoin(player);
            Main.plugin.sql.savePlayerCount(current);
        }
    }

    @EventHandler(priority = EventPriority.MONITOR)
    public void onMessage(AsyncPlayerChatEvent event) {
        Player player = event.getPlayer();

        Main.plugin.playerStatsManager.incrementStat(player, PlayerStatsManager.StatType.MESSAGES_SENT);
    }

    @EventHandler(priority = EventPriority.MONITOR)
    public void onCommand(PlayerCommandPreprocessEvent event) {
        Player player = event.getPlayer();
        String cmd = event.getMessage().toLowerCase();

        if (cmd.startsWith("/ban") || cmd.startsWith("/kick") || cmd.startsWith("/mute") || cmd.startsWith("/warn")) {
            Main.plugin.playerStatsManager.incrementStat(player, PlayerStatsManager.StatType.PUNISHMENTS);
        }
    }
}
