package com.zeshanaslam.willowcore.connection;

import com.zeshanaslam.willowcore.Main;

import java.sql.*;

public class SQL {

    private Connection connection;

    public SQL() {
        try {
            Class.forName("com.mysql.jdbc.Driver").newInstance();
            connection = DriverManager.getConnection("jdbc:mysql://" + Main.config.ip + "/" + Main.config.db + "?user=" + Main.config.user + "&password=" + Main.config.pass);
        } catch (Exception e) {
            e.printStackTrace();
        }
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
}
