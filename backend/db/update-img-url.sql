UPDATE birds
SET img_url = REPLACE(img_url, './imgs/aves/', '../backend/imgs/')
WHERE img_url LIKE './imgs/aves/%';
