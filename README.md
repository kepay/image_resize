/* 
// header('Content-Type: image/jpeg');
// $img = image_resize('C:/Users/kepay/Desktop/test.jpg', array(array(null, 200, 200, 80, true, true)));
//
// return:
// false (bool) // произошла ошибка загрузки, причины: слишком большой размер, файл не подходящего размера...
// [
//	[object image, color #, link],
//	false // при ошибке 
// ] (array) // все картинки созданы успешно
//
// внимание! миниатюры сохраняются в jpg
//
// аргумент 1 (string):
// путь до исходника 'image/img.jpg'
//
// аргумент 2 (array):
// array(
// 	// первый требуемый размер
// 	array(
// 	 'link' (string) // полный путь до место сохранения 'image/img.jpg'
// 	 width (int)
// 	 height (int)
// 	 quality (int) // качество 0-100
// 	 scissors (bool) // true: не выходить из рамки / false: обрезать и вписать четко в рамки
// 	 increase (bool) // false: если исходник меньше, то исходник растянется до требуемого размера / true: если исходник меньше, то исходник не растянется до требуемого размера	
// 	)
// );
//
*/