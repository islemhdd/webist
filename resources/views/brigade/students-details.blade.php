<x-brigade css="students-details">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="studentsDetails()">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Students Details</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Complete overview of students data</p>
                </div>
                <a href="{{ route('brigade.statistics', ['id' => $officer->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Statistics
                </a>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search
                            Students</label>
                        <input type="text" x-model="searchQuery" @input="filterStudents()"
                            placeholder="Search by name or matricule..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by
                            Grade</label>
                        <select x-model="gradeFilter" @change="filterStudents()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">All Grades</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by
                            Status</label>
                        <select x-model="statusFilter" @change="filterStudents()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="consigned">Consigned</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-500 rounded-full">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Students</p>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100" x-text="totalStudents">
                                {{ $students->count() }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-r from-green-100 to-green-200 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-full">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Active</p>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100" x-text="activeStudents">
                                {{ $students->where('consigned', 0)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-r from-red-100 to-red-200 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-500 rounded-full">
                            <i class="fas fa-ban text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">Consigned</p>
                            <p class="text-2xl font-bold text-red-900 dark:text-red-100" x-text="consignedStudents">
                                {{ $students->where('consigned', 1)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-r from-purple-100 to-purple-200 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-500 rounded-full">
                            <i class="fas fa-calendar-week text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Weekend Choices</p>
                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100" x-text="weekendChoices">
                                {{ $students->whereNotNull('choix')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="overflow-hidden rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Students List</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <span x-text="filteredStudents.length"></span> students
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Grade
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Weekend Choice
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="student in filteredStudents" :key="student.matricule">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300"
                                        x-text="student.matricule">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        <span x-text="student.nom + ' ' + student.prenom"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="student.section ? student.section.code : 'N/A'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span
                                            x-text="student.grade + (student.grade == 1 ? 'st' : student.grade == 2 ? 'nd' : 'rd') + ' Year'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-if="student.choix">
                                            <span x-text="student.choix" :class="getChoixClass(student.choix)"
                                                class="px-2 py-1 text-xs font-medium rounded-full">
                                            </span>
                                        </template>
                                        <template x-if="!student.choix">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                                No Choice
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-if="student.consigned">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                Consigned
                                            </span>
                                        </template>
                                        <template x-if="!student.consigned">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                Active
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="viewStudent(student.matricule)"
                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <i class="fas fa-eye"></i>
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function studentsDetails() {
            return {
                students: {!! json_encode(
                    $students->map(function ($student) {
                            return [
                                'matricule' => $student->matricule,
                                'nom' => $student->nom,
                                'prenom' => $student->prenom,
                                'grade' => $student->grade,
                                'choix' => $student->choix,
                                'consigned' => $student->consigned,
                                'section' => $student->section ? ['code' => $student->section->code()] : null,
                            ];
                        })->toArray(),
                ) !!},
                filteredStudents: [],
                searchQuery: '',
                gradeFilter: '',
                statusFilter: '',

                // Statistics
                totalStudents: {{ $students->count() }},
                activeStudents: {{ $students->where('consigned', 0)->count() }},
                consignedStudents: {{ $students->where('consigned', 1)->count() }},
                weekendChoices: {{ $students->whereNotNull('choix')->count() }},

                init() {
                    this.filteredStudents = [...this.students];
                },

                filterStudents() {
                    this.filteredStudents = this.students.filter(student => {
                        const matchesSearch = !this.searchQuery ||
                            student.nom.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            student.prenom.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            student.matricule.toString().includes(this.searchQuery);

                        const matchesGrade = !this.gradeFilter ||
                            student.grade.toString() === this.gradeFilter;

                        const matchesStatus = !this.statusFilter ||
                            (this.statusFilter === 'active' && !student.consigned) ||
                            (this.statusFilter === 'consigned' && student.consigned);

                        return matchesSearch && matchesGrade && matchesStatus;
                    });
                },

                getChoixClass(choix) {
                    switch (choix) {
                        case 'ven':
                            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200';
                        case 'sam':
                            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                        case '48h':
                            return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200';
                        case '36h':
                            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                        default:
                            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200';
                    }
                },

                viewStudent(matricule) {
                    // Redirect to student details page (you can customize this)
                    window.location.href = `/{{ $officer->id }}/students?search=${matricule}`;
                }
            }
        }
    </script>
</x-brigade>
