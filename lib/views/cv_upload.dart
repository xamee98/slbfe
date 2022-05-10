import 'package:flutter/material.dart';
import 'package:slbfe/main.dart';

class CvUpload extends StatelessWidget {
  const CvUpload({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppScreen();
  }
}

class AppScreen extends StatefulWidget {
  const AppScreen({Key? key}) : super(key: key);

  @override
  State<AppScreen> createState() => _AppScreenState();
}

class _AppScreenState extends State<AppScreen> {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: Text('Upload your CV'),
        ),
        body: Padding(
          padding: EdgeInsets.all(10.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            mainAxisSize: MainAxisSize.max,
            children: [
              
            ],
          ),
        ),
      ),
    );
  }
}
