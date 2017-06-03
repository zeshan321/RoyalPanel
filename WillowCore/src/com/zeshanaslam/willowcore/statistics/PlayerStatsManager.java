package com.zeshanaslam.willowcore.statistics;

import com.zeshanaslam.willowcore.Main;
import org.bukkit.entity.Player;

import java.util.HashMap;

public class PlayerStatsManager {

    public HashMap<String, Integer> stats;

    // Other
    public HashMap<String, Long> playTime;

    public PlayerStatsManager() {
        stats = new HashMap<>();
        playTime = new HashMap<>();
    }

    public void incrementStat(Player player, StatType statType) {
        String key = player.getUniqueId().toString() + "-" + statType.name();

        if (stats.containsKey(key)) {
            int value = stats.get(key);
            value++;

            stats.put(key, value);
        } else {
            stats.put(key, 1);
        }
    }

    public void addStat(Player player, StatType statType, int amount) {
        String key = player.getUniqueId().toString() + "-" + statType.name();

        if (stats.containsKey(key)) {
            int value = stats.get(key) + amount;

            stats.put(key, value);
        } else {
            stats.put(key, amount);
        }
    }

    public void loadPlayerStats(Player player) {
        for (StatType statType : StatType.values()) {
            String key = player.getUniqueId().toString() + "-" + statType.name();
            int value = Main.plugin.sql.getStatValue(player, statType);

            if (stats.containsKey(key)) {
                value = value + stats.get(key);

                stats.put(key, value);
            } else {
                stats.put(key, value);
            }
        }
    }

    public void savePlayerStats(Player player) {
        for (StatType statType : StatType.values()) {
            String key = player.getUniqueId().toString() + "-" + statType.name();
            int value = stats.get(key);

            // Remove stat
            stats.remove(key);

            // Send to db
            Main.plugin.sql.saveStatValue(player, statType, String.valueOf(value));
        }

        // Remove from play time
        playTime.remove(player.getUniqueId().toString());
    }

    public enum StatType {
        PLAY_TIME,
        MESSAGES_SENT,
        PUNISHMENTS,
        LOGIN
    }
}
