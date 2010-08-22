<operation resource="imagetag" method="create">
    <imagetag id="<?= $id ?>">
        <image id="<?= $photoid ?>" />
        <person id="<?= $personid ?>" />
        <owner id="<?= $ownerid ?>" />
        <geometry>
            <left><?= $left ?></left>
            <top><?= $top ?></top>
            <width><?= $width ?></width>
            <height><?= $height ?></height>
        </geometry>
    </imagetag>
</operation>
