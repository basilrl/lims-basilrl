<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @page {
        background-repeat: no-repeat;
        width: 100%;
        background-size: cover !important;
        margin-top: 5.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
    }

    table {
        table-layout: fixed;
        width: 100% !important;
    }

    th {
        font-size: 13px;
        text-transform: uppercase !important;
        padding: 5px;
        font-weight: bold;
    }

    td {
        font-size: 13px;
    }

    * {
        font-size: 13px;
    }
</style>

<body>
    <div class="bg_image">
        <h3>APPROVAL FOR <?php echo strtoupper($result->item_name); ?> </h3>
        <h4> Details regarding the <?php echo strtoupper($result->item_name); ?> are provided below:  </h4>
        <table style="font-size:14px; width:100%;">
            <tr>
                <th>Category Name</th>
                <td>:</td>
                <td><?php echo $result->category_name ?></td>
            </tr>
            <tr>
                <th>Minimum Quantity Required</th>
                <td>:</td>
                <td><?php echo $result->min_quantity_required ?></td>
            </tr>
            <tr>
                <th>Current Quantity Needed</th>
                <td>:</td>
                <td><?php echo $result->current_requirement . " " . $result->unit ?></td>
            </tr>
            <tr>
                <th>Requirement Reason</th>
                <td>:</td>
                <td><?php echo $result->requirement_reason ?></td>
            </tr>
            <tr>    
                <th>Requirement Raised By</th>
                <td>:</td>
                <td><?php echo $result->created_by ?></td>
            </tr>
        </table>
    </div>
</body>
</html>