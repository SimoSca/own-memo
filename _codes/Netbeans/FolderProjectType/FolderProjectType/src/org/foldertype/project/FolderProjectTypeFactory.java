package org.foldertype.project;

import java.io.IOException;
import org.netbeans.api.project.Project;
import org.netbeans.spi.project.ProjectFactory;
import org.netbeans.spi.project.ProjectState;
import org.openide.filesystems.FileObject;
import org.openide.util.lookup.ServiceProvider;

/**
 *
 * @author Simone Scardoni
 */
@ServiceProvider(service = ProjectFactory.class)
public class FolderProjectTypeFactory implements ProjectFactory {

	public static final String PROJECT_FILE1 = ".netbeans-folder";
	public static final String PROJECT_FILE2 = "netbeans-folder.txt";

	//Specifies when a project is a project, i.e.,
	//if ".netbeans-folder" or "netbeans-folder.txt" is present in a folder:
	@Override
	public boolean isProject(FileObject projectDirectory) {
		boolean t1 = projectDirectory.getFileObject(PROJECT_FILE1) != null;
		boolean t2 = projectDirectory.getFileObject(PROJECT_FILE2) != null;
		return t1 || t2;
	}

	//Specifies when the project will be opened, i.e., if the project exists:
	@Override
	public Project loadProject(FileObject dir, ProjectState state) throws IOException {
		return isProject(dir) ? new FolderProjectType(dir, state) : null;
	}

	@Override
	public void saveProject(final Project project) throws IOException, ClassCastException {
		// leave unimplemented for the moment
	}

}
