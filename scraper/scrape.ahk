#NoEnv  ; Recommended for performance and compatibility with future AutoHotkey releases.
; #Warn  ; Enable warnings to assist with detecting common errors.
SendMode Input  ; Recommended for new scripts due to its superior speed and reliability.
SetWorkingDir %A_ScriptDir%  ; Ensures a consistent starting directory.
#SingleInstance
CoordMode, Mouse, Client










F6::
{
	OpenFileAndRun()
	return
}
F7::
{
	StartRunner()
	return
}
F8::
{
	FileSelectFolder, LoadedFolder
	Loop, %LoadedFolder%\*.*, 2
	{
		FolderPath = %LoadedFolder%\%A_LoopFileName%
		ParseFolder(FolderPath)
	}
	
	MsgBox Done!!!
	return
}


StartRunner() {
	FileSelectFolder, LoadedFolder	
	MsgBox Done!
	return
}

OpenFileAndRun() {
	FileSelectFile SelectedFile
	ParseLoadedFile(SelectedFile)
	
	MsgBox Done!
	return
}

ParseFolder(LoadedFolder) {
	Loop %LoadedFolder%\*.*
	{
		if(InStr(A_LoopFileName, ".txt")) {
			LoadedFile = %LoadedFolder%\%A_LoopFileName%
			ParseLoadedFile(LoadedFile)
		}
	}
	return
}

ParseLoadedFile(LoadedFile) {
	Loop, read, %LoadedFile%
	{
		ArrayLines := StrSplit(A_LoopReadLine, ":")
		if(ArrayLines[3] == 1) {
			ParseLine(ArrayLines[2])
		}
	}
}

ParseLine(id) {
	WinActivate, ahk_exe chrome.exe
	Send ^l
	Sleep, 300
	SendInput {Raw}https://my.gumtree.com/ajax/location/children?id=%id%
	Send {Enter}
	Sleep, 1200
	Send ^a
	Sleep, 300
	Send ^c
	Sleep, 300
	WinActivate, ahk_exe notepad.exe
	Sleep, 300
	Send ^a
	Sleep, 300
	Send ^v
	Sleep, 300
	Send ^s
	Sleep, 300
	WinActivate, ahk_exe firefox.exe	
	Sleep, 300
	Send ^r
	Sleep, 500
}