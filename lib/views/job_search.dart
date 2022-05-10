import 'package:flutter/material.dart';

class JobSearch extends StatelessWidget {
  const JobSearch({Key? key}) : super(key: key);

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
          title: const Text('Search for Jobs'),
          actions: [
            IconButton(
              onPressed: () {
                // Search for jobs
              },
              icon: const Icon(Icons.search),
            ),
          ],
        ),
        body: Padding(
          padding: EdgeInsets.all(8.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.center,
            mainAxisSize: MainAxisSize.max,
            children: [
              TextField(
                obscureText: false,
                // controller: nameController,
                decoration: InputDecoration(
                  border: OutlineInputBorder(),
                  labelText: 'Enter Job Name',
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
