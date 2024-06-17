SELECT estado, count(*) as contador FROM `calidad_productos_analizados` group by estado;
SELECT estado, count(*) as contador FROM `calidad_especificacion_productos` group by estado;
SELECT b.tipo_producto, count(a.id_especificacion) contador FROM `calidad_especificacion_productos` as a left join calidad_productos as b on a.id_producto=b.id group by b.tipo_producto;