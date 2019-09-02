<table>
  <tbody>
    <tr>
      <th>ユーザID</th>
      <td>
        <!-- ここの$userNameはどこから取得したものか？ -->
        <input type="text" name="user_name" value="<?= $this->escape($user_name); ?>" />
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <input type="password" name="password" value="<?= $this->escape($password); ?>" />
      </td>
    </tr>
  </tbody>
</table>