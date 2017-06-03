package com.zeshanaslam.willowcore.connection;

import com.zeshanaslam.willowcore.Main;
import com.zeshanaslam.willowcore.statistics.PlayerStatsManager;
import org.bukkit.entity.Player;

import java.sql.*;
import java.util.Calendar;
import java.util.Date;

public class SQL {

    private Connection connection;

    public SQL() {
        try {
            Class.forName("com.mysql.jdbc.Driver").newInstance();
            connection = DriverManager.getConnection("jdbc:mysql://" + Main.plugin.config.ip + "/" + Main.plugin.config.db + "?user=" + Main.plugin.config.user + "&password=" + Main.plugin.config.pass);
        } catch (Exception e) {
            e.printStackTrace();
        }

        // Default sql queries
        //clearOldPlayerCount();
    }

    public boolean isValidUser(String username, String password) {
        try {
            String select = "select * from users where BINARY username=? and password=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, username);
            preparedStatement.setString(2, password);

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) return true;
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return false;
    }

    public boolean containsLastJoin(Player player) {
        try {
            String select = "select * from stats where uuid=? and stat=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, player.getUniqueId().toString());
            preparedStatement.setString(2, "LAST_JOIN");

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) {
                return true;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return false;
    }

    public void saveLastJoin(Player player) {
        Calendar calendar = Calendar.getInstance();
        calendar.setTime(new Date(System.currentTimeMillis()));
        String date = calendar.get(Calendar.YEAR) + "-" + (calendar.get(Calendar.MONTH) + 1) + "-" + calendar.get(Calendar.DAY_OF_MONTH);

        if (containsLastJoin(player)) {
            try {
                String update = "update stats SET statvalue=? where uuid=? and stat=?";

                PreparedStatement preparedStatement = connection.prepareStatement(update);
                preparedStatement.setString(1, date);
                preparedStatement.setString(2, player.getUniqueId().toString());
                preparedStatement.setString(3, "LAST_JOIN");

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        } else {
            try {
                String insert = "insert into stats (uuid, stat, statvalue) values (?, ?, ?)";

                PreparedStatement preparedStatement = connection.prepareStatement(insert);
                preparedStatement.setString(1, player.getUniqueId().toString());
                preparedStatement.setString(2, "LAST_JOIN");
                preparedStatement.setString(3, date);

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    public String getLastJoin(Player player) {
        try {
            String select = "select * from stats where uuid=? and stat=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, player.getUniqueId().toString());
            preparedStatement.setString(2, "LAST_JOIN");

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) {
                return resultSet.getString("statvalue");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return null;
    }

    public int getStatValue(Player player, PlayerStatsManager.StatType statType) {
        try {
            String select = "select * from stats where uuid=? and stat=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, player.getUniqueId().toString());
            preparedStatement.setString(2, statType.name());

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) {
                return Integer.valueOf(resultSet.getString("statvalue"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return 0;
    }

    public boolean containsStatValue(Player player, PlayerStatsManager.StatType statType) {
        try {
            String select = "select * from stats where uuid=? and stat=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, player.getUniqueId().toString());
            preparedStatement.setString(2, statType.name());

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) {
                return true;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return false;
    }

    public void saveStatValue(Player player, PlayerStatsManager.StatType statType, String value) {
        if (containsStatValue(player, statType)) {
            try {
                String update = "update stats SET statvalue=? where uuid=? and stat=?";

                PreparedStatement preparedStatement = connection.prepareStatement(update);
                preparedStatement.setString(1, value);
                preparedStatement.setString(2, player.getUniqueId().toString());
                preparedStatement.setString(3, statType.name());

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        } else {
            try {
                String insert = "insert into stats (uuid, stat, statvalue) values (?, ?, ?)";

                PreparedStatement preparedStatement = connection.prepareStatement(insert);
                preparedStatement.setString(1, player.getUniqueId().toString());
                preparedStatement.setString(2, statType.name());
                preparedStatement.setString(3, value);

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    public int containsPlayerCount(String date) {
        try {
            String select = "select * from playercount where countdate=?";

            PreparedStatement preparedStatement = connection.prepareStatement(select);
            preparedStatement.setString(1, date);

            ResultSet resultSet = preparedStatement.executeQuery();

            while (resultSet.next()) {
                return resultSet.getInt("playercount");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return -1;
    }

    public void savePlayerCount(String date) {
        int count = containsPlayerCount(date);

        if (count != -1) {
            try {
                String update = "update playercount SET playercount=? where countdate=?";

                PreparedStatement preparedStatement = connection.prepareStatement(update);
                preparedStatement.setInt(1, count + 1);
                preparedStatement.setString(2, date);

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        } else {
            try {
                String insert = "insert into playercount (countdate, playercount) values (?, ?)";

                PreparedStatement preparedStatement = connection.prepareStatement(insert);
                preparedStatement.setString(1, date);
                preparedStatement.setInt(2, 1);

                preparedStatement.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    public void clearOldPlayerCount() {
        try {
            String query = "DELETE FROM playercount WHERE id ORDER BY id DESC LIMIT -1 OFFSET 10";

            PreparedStatement preparedStatement = connection.prepareStatement(query);

            preparedStatement.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
