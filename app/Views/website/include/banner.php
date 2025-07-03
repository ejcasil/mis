<div class="wrapper">
    <main>
        <ul class='slider'>
            <?php if ($banners) : ?>
                <?php foreach ($banners as $banner): ?>
                    <li class='item' style="background-image: url('<?= base_url('writable/uploads/' . $banner->img_path); ?>')">
                        <div class='content'>
                            <h2 class='title clamped-title'><?= $banner->title ?? ''; ?></h2>
                            <p class='description clamped-desc'><?= $banner->description ?? ''; ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <nav class='nav'>
            <ion-icon class='btn prev' name="arrow-back-outline"></ion-icon>
            <ion-icon class='btn next' name="arrow-forward-outline"></ion-icon>
        </nav>
    </main>
</div>