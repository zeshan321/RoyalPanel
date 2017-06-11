package com.zeshanaslam.willowcore.commands;

import com.zeshanaslam.willowcore.Main;
import com.zeshanaslam.willowcore.statistics.PlayerStatsManager;
import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.command.Command;
import org.bukkit.command.CommandExecutor;
import org.bukkit.command.CommandSender;
import org.bukkit.entity.Player;

import java.util.concurrent.TimeUnit;

public class StatsCommand implements CommandExecutor {

    private final Main plugin;

    public StatsCommand(Main plugin) {
        this.plugin = plugin;
    }

    @Override
    public boolean onCommand(CommandSender sender, Command command, String label, String[] args) {
        if (args.length <= 0) {
            if (sender instanceof Player) {
                Player player = (Player) sender;

                sendStats(player);
            } else {
                sender.sendMessage(ChatColor.RED + "Usage from console: /stats <player>");
                return false;
            }
        } else {
            Player player = Bukkit.getPlayer(args[0]);
            if (player != null) {
                sendStats(player);
            } else {
                sender.sendMessage(ChatColor.RED + "Unable to find player: " + args[0] + ".");
                return false;
            }
        }

        return true;
    }

    public void sendStats(Player player) {
        player.sendMessage(ChatColor.GRAY + "Stats for " + ChatColor.RED + player.getName() + ChatColor.GRAY + ":");
        player.sendMessage("");
        player.sendMessage(ChatColor.GRAY + "Total logins: " + ChatColor.RED + plugin.playerStatsManager.stats.get(player.getUniqueId().toString() + "-" + PlayerStatsManager.StatType.LOGIN.name()));

        long difference = System.currentTimeMillis() - Main.plugin.playerStatsManager.playTime.get(player.getUniqueId().toString());
        int minutes = (int) (difference / (60 * 1000) % 60);
        if (minutes <= 60) {
            player.sendMessage(ChatColor.GRAY + "Current time: " + ChatColor.RED + minutes + ChatColor.GRAY + " minutes");
        } else {
            double hours = minutes / 60;
            hours = Math.round(hours);

            player.sendMessage(ChatColor.GRAY + "Current time: " + ChatColor.RED + hours + ChatColor.GRAY + " hours");
        }

        minutes = plugin.playerStatsManager.stats.get(player.getUniqueId().toString() + "-" + PlayerStatsManager.StatType.PLAY_TIME.name());
        if (minutes <= 60) {
            player.sendMessage(ChatColor.GRAY + "Play time: " + ChatColor.RED + minutes + ChatColor.GRAY + " minutes");
        } else {
            double hours = minutes / 60;
            hours = Math.round(hours);

            player.sendMessage(ChatColor.GRAY + "Play time: " + ChatColor.RED + hours + ChatColor.GRAY + " hours");
        }

        player.sendMessage(ChatColor.GRAY + "Total punishments: " + ChatColor.RED + plugin.playerStatsManager.stats.get(player.getUniqueId().toString() + "-" + PlayerStatsManager.StatType.PUNISHMENTS.name()));
        player.sendMessage(ChatColor.GRAY + "Messages sent: " + ChatColor.RED + plugin.playerStatsManager.stats.get(player.getUniqueId().toString() + "-" + PlayerStatsManager.StatType.MESSAGES_SENT.name()));
    }
}
