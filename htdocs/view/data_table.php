<tbody>
    <?php foreach ($$table_data as $values): ?>
    <tr>
        <?php foreach($values as $value): ?>
        <td><?php echo($value); ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</tbody>