package com.zeshanaslam.willowcore.commands;

import com.zeshanaslam.willowcore.Main;
import com.zeshanaslam.willowcore.statistics.PlayerStatsManager;
import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.OfflinePlayer;
import org.bukkit.command.Command;
import org.bukkit.command.CommandExecutor;
import org.bukkit.command.CommandSender;
import org.bukkit.entity.Player;

import java.util.HashMap;

public class StatsCommand implements CommandExecutor {

    private final Main plugin;

    public StatsCommand(Main plugin) {
        this.plugin = plugin;
    }

    @Override
    public boolean onCommand(CommandSender sender, Command command, String label, String[] args) {
        if (!sender.hasPermission("WillowCore.stats")) {
            return false;
        }
        if (args.length <= 0) {
            if (sender instanceof Player) {
                Player player = (Player) sender;

                sendStats(sender, player.getName(), player.getUniqueId().toString(), true);
            } else {
                sender.sendMessage(ChatColor.RED + "Usage from console: /stats <player>");
                return false;
            }
        } else {
            Player player = Bukkit.getPlayer(args[0]);
            if (player != null) {
                sendStats(sender, player.getName(), player.getUniqueId().toString(), true);
            } else {
                OfflinePlayer offlinePlayer = Bukkit.getOfflinePlayer(args[0]);

                if (offlinePlayer != null && offlinePlayer.getUniqueId() != null) {
                    sendStats(sender, offlinePlayer.getName(), offlinePlayer.getUniqueId().toString(), false);
                } else {
                    sender.sendMessage(ChatColor.RED + "Unable to find player: " + args[0] + ".");
                }
                return false;
            }
        }

        return true;
    }

    public void sendStats(CommandSender sender, String name, String uuid, boolean online) {
        if (online) {
            sender.sendMessage(ChatColor.GRAY + "Stats for " + ChatColor.RED + name + ChatColor.GRAY + ":");
            sender.sendMessage("");
            sender.sendMessage(ChatColor.GRAY + "Total logins: " + ChatColor.RED + plugin.playerStatsManager.stats.get(uuid+ "-" + PlayerStatsManager.StatType.LOGIN.name()));
            sender.sendMessage(ChatColor.GRAY + "Play time: " + ChatColor.RED + getPlayTime(0, uuid, true));
            sender.sendMessage(ChatColor.GRAY + "Total punishments: " + ChatColor.RED + plugin.playerStatsManager.stats.get(uuid + "-" + PlayerStatsManager.StatType.PUNISHMENTS.name()));
            sender.sendMessage(ChatColor.GRAY + "Messages sent: " + ChatColor.RED + plugin.playerStatsManager.stats.get(uuid + "-" + PlayerStatsManager.StatType.MESSAGES_SENT.name()));
        } else {
            HashMap<String, String> stats = plugin.sql.getAllStats(uuid);

            if (!stats.isEmpty()) {
                sender.sendMessage(ChatColor.GRAY + "Stats for " + ChatColor.RED + name + ChatColor.GRAY + ":");
                sender.sendMessage("");
                sender.sendMessage(ChatColor.GRAY + "Total logins: " + ChatColor.RED + stats.get(PlayerStatsManager.StatType.LOGIN.name()));
                sender.sendMessage(ChatColor.GRAY + "Play time: " + ChatColor.RED + getPlayTime(Integer.valueOf(stats.get(PlayerStatsManager.StatType.PLAY_TIME.name())), uuid, false));
                sender.sendMessage(ChatColor.GRAY + "Total punishments: " + ChatColor.RED + stats.get(PlayerStatsManager.StatType.PUNISHMENTS.name()));
                sender.sendMessage(ChatColor.GRAY + "Messages sent: " + ChatColor.RED + stats.get(PlayerStatsManager.StatType.MESSAGES_SENT.name()));
            } else {
                sender.sendMessage(ChatColor.RED + "Unable to find player: " + name + ".");
            }
        }
    }

    public String getPlayTime(int minutes, String uuid, boolean online) {
        String output;

        if (online) {
            long difference = System.currentTimeMillis() - Main.plugin.playerStatsManager.playTime.get(uuid);
            minutes = (int) (difference / (60 * 1000) % 60);
        }

        if (minutes > 60) {
            int hours = (int) Math.floor(minutes / 60);

            if (hours > 24) {
                int days = Math.round(hours / 24);

                output = days + "" +  ChatColor.GRAY + " days";
            } else {
                output = hours + "" +  ChatColor.GRAY + " hours";
            }
        } else {
            output = minutes + "" +  ChatColor.GRAY + " minutes";
        }

        return output;
    }
}
