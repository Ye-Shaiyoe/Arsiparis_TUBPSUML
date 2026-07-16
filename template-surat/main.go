package main

func main() {
	doc, err := docxtpl.ParseFromFilename("template.docx")
	if err != nil {
		panic(err)
	}

	data := struct {
		Nama  string
		Nomor string
	}{
		Nama:  "Budi Santoso",
		Nomor: "INV-00123",
	}

	err = doc.Render(data)
	if err != nil {
		panic(err)
	}

	err = doc.SaveToFile("hasil.docx")
	if err != nil {
		panic(err)
	}
}
